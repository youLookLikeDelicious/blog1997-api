<?php

namespace App\Repository;

use App\Facades\Page;
use App\Model\Comment as Model;
use App\Facades\CacheModel;
use App\Model\Article;
use App\Contract\Repository\Comment as RepositoryComment;
use Illuminate\Support\Facades\Cache;

class Comment implements RepositoryComment
{
    /**
     * @var App\Model\Comment
     */
    protected $model;

    /**
     * 获取的评论
     * 
     * @var array
     */
    protected $comments;

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
            ->where('verified', 'yes');

        // 返回的分页数据
        $this->comments = Page::paginate($commentQuery, 12);

        $this->comments['records'] = $this->comments['records']
            ->makeHidden(['able_id', 'able_type'])
            ->toArray();

        $commented = 0;
        if ($commentableType == Article::class) {
            $commented = CacheModel::getArticleCommented($commentableId);
        } else if ($commentableType == 'Blog1997') {
            $commented = CacheModel::getLeaveMessageCommented();
        }
        
        // 没有相关的评论,不在尝试获取回复
        if (!count($this->comments['records']) && !$commented) {
            return $this->getEmptyComment();
        }

        $this->getReplyForEachComment();

        $result = ['records' => $this->comments['records']];

        // 添加页数信息
        $result['p'] = $this->comments['pagination']['currentPage'];
        $result['pages'] = $this->comments['pagination']['last'];

        return $result;
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
        return Cache::remember($key, (60 * 60 * mt_rand(0, 200)), function () use ($commentableId, $commentableType) {
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
    public function getReply($rootId, $offset)
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
     * 为每条评论获取回复
     */
    protected function getReplyForEachComment ()
    {
        // 生成 获取回复的 query
        $replyQuery = $this->model
            ->with(['user:id,name,avatar', 'receiver:id,name,avatar'])
            ->select(['comment.id', 'level', 'liked', 'user_id', 'content', 'able_id', 'root_id', 'reply_to']);

         // 尝试为每条评论获取回复
         foreach ($this->comments['records'] as $k => $v) {

            // 获取 该条评论 缓存的点赞数
            $commentLiked = CacheModel::getCommentLiked($v['id']);
            $this->comments['records'][$k]['liked'] += $commentLiked;

            // 获取缓存的 评论回复数量
            $commentCommented = CacheModel::getCommentCommented($v['id']);
            $this->comments['records'][$k]['commented'] += $commentCommented;

            // 如果 该评论没有相关的回复
            if (!$this->comments['records'][$k]['commented']) {
                $this->comments['records'][$k]['replies'] = [];
                continue;
            }

            $replyTmpQuery = clone $replyQuery;

            $replies = $replyTmpQuery
                ->where('root_id', $v['id'])
                ->limit(3)
                ->get()
                ->toArray();

            // 获取缓存的 回复的点赞数
            foreach ($replies as $key => $vv) {
                $replies[$key]['liked'] += CacheModel::getCommentLiked($vv['id']);
            }

            $this->comments['records'][$k]['replies'] = $replies;
        }
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
     * @return array
     */
    public function getUnverified()
    {
        $comments = $this->model
            ->with('user:id,name,avatar')
            ->select(['id', 'content', 'able_id', 'able_type', 'user_id', 'created_at', 'verified']) 
            ->where('verified', 'no');

        $result = Page::paginate($comments);

        return $result;
    }
}
