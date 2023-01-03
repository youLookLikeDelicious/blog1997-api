<?php

namespace App\Repository;

use App\Facades\Page;
use App\Model\Article;
use App\Facades\CacheModel;
use App\Model\Comment as Model;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\CommonCollection;
use App\Contract\Repository\Comment as RepositoryComment;

class Comment implements RepositoryComment
{
    /**
     * @var \App\Model\Comment
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected function rememberComment($commentableId, $commentableType)
    {
        // 查询评论
        $commentQuery = $this->model
            ->select(['id', 'content', 'user_id', 'level', 'reply_to', 'created_at', 'able_type', 'able_id', 'liked', 'commented'])
            ->with('user:id,name,avatar')
            ->where('able_id', $commentableId)
            ->where('able_type', $commentableType)
            ->where('level', 1)
            ->where('verified', 'yes')
            ->with(['replies' => function ($q) {
                $q->with('receiver:id,name,avatar')
                    ->select(['comment.id', 'level', 'liked', 'user_id', 'content', 'able_id', 'root_id', 'reply_to']);
            }]);

        // 返回的分页数据
        $result = Page::paginate($commentQuery, 12);

        $records = $this->syncCommentsCache($result['records']);

        $commented = 0;
        if ($commentableType == Article::class) {
            $commented = CacheModel::getArticleCommented($commentableId);
        } else if ($commentableType == 'Blog1997') {
            $commented = CacheModel::getLeaveMessageCommented();
        }
        
        // 没有相关的评论,不在尝试获取回复
        if (!count($result) && !$commented) {
            return $this->getEmptyComment();
        }

        // 添加页数信息
        $p = $result['pagination']['currentPage'];
        $pages = $result['pagination']['last'];

        return compact('records', 'p', 'pages');
    }

    /**
     * 获取评论
     * 
     * @param int $commentableId
     * @param string $commentableType
     * @return array
     */
    public function getComment($commentableId, $commentableType)
    {
        $key = 'comment' . $commentableId . $commentableType . request()->input('p', 1);
        $seconds = getCacheSeconds(60 * mt_rand(1, 200));
        return Cache::remember($key, $seconds, function () use ($commentableId, $commentableType) {
            return $this->rememberComment($commentableId, $commentableType);
        });
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
        $comment = $this->model
            ->with(['user:id,name,avatar', 'receiver:id,name,avatar'])
            ->select(['comment.id', 'user_id', 'liked', 'content', 'commented', 'root_id', 'level'])
            ->where('root_id', $rootId)
            ->where('level', '!=', '1')
            ->limit(10)
            ->offset($offset)
            ->get();

        return $comment->toArray();
    }

    /**
     * 获取comment able type
     * 
     * @param $id
     * @return \App\Model\Comment | null
     */
    public function getCommentPolymorphById ($id)
    {
        return $this->model->select('able_type', 'able_id', 'id')->find($id);
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
        $comments->flatten()->each(function($comment) {
            // 获取 该条评论 缓存的点赞数
            if ($commentLiked = CacheModel::getCommentLiked($comment->id)) {
                $comment->liked += $commentLiked;
            }

            // 获取缓存的 评论回复数量
            if ($commentCommented = CacheModel::getCommentCommented($comment->id)) {
                $comment->commented += $commentCommented;
            }
        });

        return $comments;
    }


    /**
     * 获取文章的留言
     * 
     * @return array
     */
    public function getLeaveMessage ()
    {
        $comments = $this->getComment(0, 'Blog1997');

        // 获取缓存的留言数量
        $commentNum = 0;

        $result = array_merge($comments, ['commented' => $commentNum]);

        return $result;
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
        return CacheModel::getLeaveMessageCommented();
    }

    /**
     * 获取单个模型
     *
     * @param int $id
     * @return App\Model\Comment
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
