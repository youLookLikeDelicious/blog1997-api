<?php

namespace App\Repository;

use App\Facades\Page;
use App\Model\MessageBox as MessageBoxModel;
use App\Contract\Repository\MessageBox as RepositoryMessageBox;
use App\Model\Article;
use App\Model\Comment;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class MessageBox implements RepositoryMessageBox
{
    /**
     * 未读的邮件信息
     *
     * @var \App\Model\MessageBox
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
        $result = Page::paginate($query);
        
        $result['records']->search(function ($item, $key) {
            if ($item->type === 'App\Model\Comment') {
                $item->load('notificationable:id,content');
            }
        });

        // 获取未读举报信息的数量
        $notHaveReadCount = $this->model
            ->selectRaw('count(id) as count')
            ->where('have_read', 'no')
            ->where('receiver', -1)
            ->first();

        $result['notHaveReadCount'] = $notHaveReadCount->count;

        $total = $this->model
            ->selectRaw('count(id) as count')
            ->where('receiver', -1)
            ->first();

        // 获取总的记录数量
        $result['total'] = $total->count;

        $result['haveRead'] = $request->input('have_read', '');

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
            ->where('receiver', -1);

        if ($type = $request->input('have_read', '')) {
            $query->where('have_read', $type);
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
            'have_read' => 'sometimes|required|in:yes,no'
        ]);
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
            ->whereIn('type', ['App\Model\Article', 'App\Model\Comment'])
            ->groupBy('type')
            ->get();

        return $result;
    }

    /**
     * Get comment notification
     *
     * @return array
     */
    public function getNotification(Request $request)
    {
        $this->validateRequest($request);
        
        $query = $this->buildNotificationQuery($request);

        $updateQuery = clone $query;
        $notifications = Page::paginate($query);

        $records = $notifications['records']->toArray();

        // 查询相互之间的回复
        foreach ($records as $key => $notification) {
            switch ($notification['type']) {
                case 'App\Model\Comment':
                    // 如果评论 的commentable是comment，判断主体是留言还是文章
                    $commentSubject = $notification['notificationable']['commentable'];

                    // 表示是评论
                    if (!empty($commentSubject['able_type'])) {
                        $records[$key]['notificationable']['commentable'] = $this->getCommentSubject($commentSubject);
                    }

                    $comments = $this->getCommentAbleComments($notification);
                    $records[$key]['notificationable']['commentable']['comments'] = $comments;
                    break;
                case 'App\Model\ThumbUp':
                    break;
            }
        }

        $notifications['records'] = $records;

        $haveRead = $request->input('have_read', '');
        $notifications['haveRead'] = $haveRead;

        $notifications['counts'] = [
            'total' => $this->statisticTotalNotification(),
            'have_read' => $this->statisticTotalNotification('yes')
        ];

        // 将未读改为已读
        if ($haveRead === 'no') {
            $updateQuery->update(['have_read' => 'yes']);
        }

        return $notifications;
    }

    /**
     * Build get notification eloquent query
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildNotificationQuery(Request $request)
    {
        // 为了使用Eloquent的加载机制，给comment表添加了 title字段
        // 也可以选择重写框架的MorphTo类，完善该功能
        // 要么使用其他的逻辑 | 使用select * 语法
        $query = $this->model->with(['notificationable' => function (MorphTo $morphTo) {
            $morphTo->select('id', 'content', 'title', 'able_type', 'able_id', 'created_at');
            $morphTo->morphWith([
                'App\Model\Comment' => ['commentable.user:id,name,avatar'],
                'App\Model\ThumbUp' => ['thumbable:id,content,title'],
            ]);
        }])->with('user:id,name,avatar')
            ->select(['id', 'type', 'content', 'have_read', 'sender', 'reported_id', 'created_at', 'updated_at'])
            ->whereRaw($this->notificationCondition())
            ->whereIn('type', ['App\Model\Comment', 'App\Model\ThumbUp'])
            ->orderBy('created_at', 'desc');

        if ($type = $request->input('have_read', '')) {
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

        if (auth()->isMaster()) {
            $userId = auth()->id();
            $query->whereRaw("receiver = {$userId} or receiver = 0");
        } else {
            $query->where('receiver', auth()->id());
        }

        if ($haveRead) {
            $query->where('have_read', $haveRead);
        }

        $total = $query->first()->total;

        return $total;
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
        if ($notification['notificationable']['able_type'] === 'App\Model\Article' || !$notification['notificationable']['able_type']) {
            $query->whereRaw("root_id = {$notification['notificationable']['id']}");
        } else {
            // 获取留言的回复
            $comment = Comment::select(['id', 'root_id'])->find($notification['notificationable']['id']);
            $query->whereRaw("(root_id = {$comment->root_id} or id = {$comment->root_id})");
        }

        $currentUserId = auth()->id();

        $query->whereRaw("(user_id = {$notification['sender']} or user_id = {$currentUserId})");

        $query->orderBy('created_at', 'ASC');

        $comments = Page::paginate($query, 5);

        return $comments;
    }

    /**
     * 获取评论的主体
     *
     * @param array $comment
     * @return Article|null
     */
    protected function getCommentSubject($comment)
    {
        if (!$comment) {
            return null;
        }

        if ($comment['level'] == 1) {
            if ($comment['able_type'] == 'App\Model\Article') {
                return Article::with('user:id,name,avatar')
                    ->select(['id', 'summary', 'created_at', 'title', 'user_id'])
                    ->find($comment['able_id']) ?: null;
            } else {
                return null;
            }
        }

        $rootComment = Comment::select(['id', 'level', 'able_type', 'able_id'])
            ->find($comment['root_id']);

        return $this->getCommentSubject($rootComment);
    }

    /**
     * Get select condition
     *
     * @return string
     */
    protected function notificationCondition()
    {
        $userId = auth()->id();

        return auth()->isMaster()
            ? "(receiver = {$userId} or receiver = 0)"
            : 'receiver = ' . $userId;
    }
}
