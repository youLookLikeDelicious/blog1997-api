<?php

namespace App\Repository;

use App\Facades\CacheModel;
use App\Models\Comment as Model;
use App\Http\Resources\CommonCollection;
use App\Contract\Repository\Comment as RepositoryComment;
use Illuminate\Support\Facades\Auth;

class Comment implements RepositoryComment
{
    /**
     * @var \App\Models\Comment
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * 获取评论
     * 
     * @param int $ableId
     * @param string $ableType
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function getComment($ableId, $ableType)
    {
        // 查询评论
        $commentQuery = $this->model
            ->select(['id', 'content', 'user_id', 'level', 'reply_to', 'created_at', 'able_type', 'able_id', 'liked', 'commented'])
            ->with('user:id,name,avatar')
            ->where('able_id', $ableId)
            ->where('able_type', $ableType)
            ->where('level', 1)
            ->where('verified', 'yes')
            ->orderBy('created_at', 'desc');

        if ($user = Auth::user()) {
            $commentQuery->withCount(['thumb' => fn ($q) => $q->where('user_id', $user->id)]);
        }

        // 返回的分页数据
        $paginator = $commentQuery->paginate(request()->input('perPage', 10));
            
        $comments = collect($paginator->items());
        
        // 为每条评论加载回复
        $comments->each(fn ($comment) => 
            $comment->load(['replies' => function ($q) {
                $q->select(['comment.id', 'level', 'liked', 'user_id', 'content', 'able_id', 'root_id', 'reply_to'])
                    ->with(['receiver:id,name,avatar'])
                    ->limit(5);
        }]));

        $this->syncCommentsCache($comments);

        return new CommonCollection($paginator);
    }

    /**
     * 获取评论的回复
     * 
     * @param int $rootId
     * @param int $offset
     * @return array
     */
    public function getReply($rootId, $offset = 0)
    {
        $query = $this->model
            ->with(['user:id,name,avatar', 'receiver:id,name,avatar'])
            ->with('commentable', fn ($q) => $q->where('id', '<>', $rootId)->with(['user:id,name,avatar', 'receiver:id,name,avatar']))
            ->select(['comment.id', 'user_id', 'liked', 'content', 'commented', 'reply_to', 'root_id', 'level', 'created_at', 'able_id', 'able_type'])
            ->where('root_id', $rootId)
            ->where('level', '!=', '1');

        $total = (clone $query)->count();

        $comment = $query->limit(10)
            ->offset($offset)
            ->get();
        
        return [$comment, $total];
    }

    /**
     * 返回空评论
     * 
     * @return array
     */
    protected function getEmptyComment ()
    {
        return [
            'records' => [],
            'commented' => 0,
            'p' => 1,
            'pages' => 1
        ];
    }

    /**
     * 同步评论的缓存数据
     * 
     * @param \Illuminate\Database\Eloquent\Collection $comments
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function syncCommentsCache ($comments)
    {
        $comments->each(function($comment) {
            // 获取 该条评论 缓存的点赞数
            if ($commentLiked = CacheModel::getCommentLiked($comment->id)) {
                $comment->liked += $commentLiked;
            }

            // 获取缓存的 评论回复数量
            if ($commentCommented = CacheModel::getCommentCommented($comment->id)) {
                $comment->commented += $commentCommented;
            }
        });
    }


    /**
     * 获取留言
     * 
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function getLeaveMessage ()
    {
        $resource = $this->getComment(0, 'Blog1997');

        return $resource;
    }

    /**
     * 获取评论留言的数量 | 记录的本身也参加计数
     * 
     * @return int
     */
    public function getCommentAndCommentReplyCount ($comment)
    {
        if ($comment->able_type === Model::class) {
            $model =  Model::selectRaw('count(id) as count, able_id')
                ->where('able_id', $comment->id)
                ->where('level', '<>', '1')
                ->groupBy('able_id')
                ->first();

            if (!$model) {
                return 1;
            }

            return $model->count + 1;
        }

        $model = Model::selectRaw('count(id) as count, root_id')
            ->where('root_id', $comment->id)
            ->where('level', '<>', '1')
            ->groupBy('root_id')
            ->first();

        $count = $model
            ? $model->count
            : 0;
        return $count + 1;
    }

    /**
     * 获取留言的数量
     *
     * @return int
     */
    public function getLeaveMessageCount ()
    {
        return $this->model->where('able_type', 'Blog1997')->count();
    }

    /**
     * 获取单个模型
     *
     * @param int $id
     * @return App\Models\Comment
     */
    public function find ($id)
    {
        return $this->model->select(['level', 'commented', 'able_id', 'able_type', 'root_id', 'id', 'article_id'])
            ->findOrFail($id);
    }

    /**
     * 统计总的评论数量
     *
     * @return integer
     */
    public function totalCommented() : int
    {
        $result = $this->model
            ->selectRaw('count(id) as count')
            ->first();

        return $result->count ?? 0;
    }

    /**
     * 获取未审核的评论
     *
     * @param \Illuminate\Support\Facades\Request $request;
     * @return CommonCollection
     */
    public function getUnverified($request)
    {
        $comments = $this->model
            ->with('user:id,name,avatar')
            ->select(['id', 'content', 'able_id', 'able_type', 'user_id', 'created_at', 'verified']) 
            ->where('verified', 'no')
            ->paginate($request->input('perPage', 10));

        return new commonCollection($comments);;
    }
}
