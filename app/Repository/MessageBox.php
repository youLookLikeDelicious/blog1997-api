<?php

namespace App\Repository;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommonCollection;
use App\Models\MessageBox as MessageBoxModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Contract\Repository\MessageBox as RepositoryMessageBox;

class MessageBox implements RepositoryMessageBox
{
    /**
     * 未读的邮件信息
     *
     * @var \App\Models\MessageBox
     */
    protected $model;

    public function __construct(MessageBoxModel $model)
    {
        $this->model = $model;
    }

    /**
     * 获取所有的举报信息
     *
     * @param Request $request
     * @return array
     */
    public function all(Request $request)
    {
        $this->validateRequest($request);

        $query = $this->buildQuery($request);

        // 分页获取数据
        $data = (clone $query)->paginate($request->input('perPage', 10));

        $result = new CommonCollection($data);
        
        // 标记为已读状态
        $query->update(['have_read' => 'yes']);

        return $result;
    }

    /**
     * Build eloquent query
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery(Request $request)
    {
        $type = $request->input('have_read');

        $query = $this->model
            ->selectRaw('id, sender, receiver, reported_id, type, content, created_at, operate, have_read')
            ->where('receiver', -1)
            ->with('notificationable:id,content');

        if ($type = $request->input('have_read', '')) {
            $query->where('have_read', $type);
        }

        // 处理结果
        if ($operate = $request->input('operate')) {
            $query->where('operate', $operate);
        }

        $query->orderBy('created_at');

        return $query;
    }

    /**
     * Validate incoming request
     *
     * @param Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'have_read' => 'sometimes|required|in:yes,no',
            'operate'   => 'sometimes|in:undo,ignore,approve'
        ]);
    }

    /**
     * 获取未读的举报内容数量
     *
     * @return int
     */
    public function getUnreadReportInfoCount()
    {
        return $this->model
            ->where('have_read', 'no')
            ->where('receiver', -1)
            ->count();
    }

    /**
     * 通过id获取消息
     * 
     * @param int $id
     * @return boolean
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * 统计文章和评论被举报的数量
     *
     * @return array
     */
    public function statisticByType()
    {
        $result = $this->model
            ->selectRaw('count(id) as count, type')
            ->where('receiver', -1)
            ->where('have_read', 'no')
            ->whereIn('type', ['article', 'comment'])
            ->groupBy('type')
            ->get();

        return $result;
    }

    /**
     * Get comment notification
     *
     * @return CommonCollection
     */
    public function getNotification(Request $request)
    {
        $this->validateRequest($request);
        
        $query = $this->buildNotificationQuery($request);

        $updateQuery = clone $query;
        $notifications = $query->paginate($request->input('perPage', 10));

        // 将未读改为已读
        if ($request->input('have_read') !== 'yes') {
            $updateQuery->update(['have_read' => 'yes']);
        }

        $result =  new CommonCollection($notifications);

        $result->each(function($notification) {
            // 为评论获取相关的回复
            if ($notification->notificationable instanceof Comment) {
                $replyPaginator = $notification->notificationable->replies()
                    ->with('user:id,name,avatar')
                    ->whereIn('user_id', [auth()->id(), $notification->sender])
                    ->orderBy('created_at')
                    ->paginate(5);

                $replies = $replyPaginator->getCollection();
                if ($notification->notificationable['id']) {
                    $replies->prepend($notification->notificationable->toArray());
                }
                $notification->notificationable->replies = $replyPaginator;
            }
        });

        // 统计未读数量
        $result->additional([
            'meta' => [
                'have_read' => $this->statisticTotalNotification('yes'),
                'total_num' => $this->statisticTotalNotification()
            ]
        ]);

        return $result;
    }

    /**
     * Build get notification eloquent query
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildNotificationQuery(Request $request)
    {
        $query = $this->model->with(['notificationable' => function (MorphTo $morphTo) {
            $morphTo->select('id', 'content', 'title', 'able_type', 'able_id', 'created_at', 'user_id');
            $morphTo->morphWith([
                Relation::getMorphedModel('comment') => ['commentable.user:id,name,avatar', 'user:id,name,avatar'],
                Relation::getMorphedModel('thumbup') => ['thumbable:id,content,title'],
            ]);
        }])->with('user:id,name,avatar')
            ->select(['id', 'type', 'content', 'have_read', 'sender', 'reported_id', 'created_at', 'updated_at'])
            ->whereRaw($this->notificationCondition())
            ->where('sender', '!=', auth()->id())
            ->whereIn('type', ['comment', 'thumbup'])
            ->orderBy('updated_at', 'desc');

        if ($type = $request->input('have_read')) {
            $query->where('have_read', $type);
        }

        return $query;
    }

    /**
     * 统计总的通知数量
     * 
     * @param string $haveRead 是否已读
     * @return integer
     */
    protected function statisticTotalNotification($haveRead = '')
    {
        $query = $this->model->selectRaw('count(id) as total');

        $query->whereRaw($this->notificationCondition());

        if ($haveRead) {
            $query->where('have_read', $haveRead);
        }

        $query->where('sender', '!=', auth()->id());

        $total = $query->first()->total;

        return $total;
    }

    /**
     * Get select condition
     *
     * @return string
     */
    protected function notificationCondition()
    {
        $userId = auth()->id();

        return auth()->user()->isMaster()
            ? "(receiver = {$userId} or receiver = 0)"
            : 'receiver = ' . $userId;
    }

    /**
     * Get commentable comments
     *
     * @param MessageBox $notification
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getCommentAbleComments($notification)
    {
        $query = Comment::select(['id', 'content', 'created_at', 'user_id', 'root_id'])
            ->with('user:id,name,avatar');


        // 获取文章的评论 以及回复
        if ($notification['notificationable']['able_type'] === 'article') {
            $query->whereRaw("root_id = {$notification['notificationable']['id']}");
        } else {
            // 获取留言的回复
            $comment = Comment::select(['id', 'root_id'])->findOrFail($notification['notificationable']['id']);
            $rootId = $comment->root_id ?: $comment->id;
            $query->where('root_id', $rootId);
        }

        $currentUserId = auth()->id();

        $query->whereRaw("(user_id = {$notification['sender']} or user_id = {$currentUserId})");

        $query->orderBy('created_at', 'ASC');

        $comments = $query->paginate(5);

        return $comments;
    }
}
