<?php

namespace App\Model;

use App\Facades\CustomAuth;
use App\Facades\RedisCache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $dateFormat = 'U';
    protected $table = 'comment';
    protected $guarded = [];
    protected $appends = [
        'thumbs'
    ];

    /**
     * 定义和点赞表的关系
     * @return MorphOne
     */
    public function getThumbsAttribute()
    {
        $thumb =  ThumbUp::select('id')
            ->where('thumbable_id', $this->id)
            ->where('thumbable_type', self::class)
            ->where('user_id', CustomAuth::id())
            ->get();

        return $thumb->count();
    }

    /**
     * 获取一级评论
     * @param $commentableId
     * @param $commentableType
     * @return array
     */
    public function getComment($commentableId, $commentableType)
    {
        // 查询评论
        $commentQuery = $this->where('commentable_id', $commentableId)
            ->where('commentable_type', $commentableType)
            ->where('level', 1)
            ->leftJoin('user as u', 'comment.user_id', 'u.id')
            ->select([
                'name',
                'liked',
                'level',
                'content',
                'user_id',
                'u.avatar',
                'reply_to',
                'commented',
                'comment.id',
                'commentable_id',
                'comment.created_at'
            ]);

        // 返回的分页数据
        $comment = \Page::paginate($commentQuery);

        $comment['records'] = $comment['records']->toArray();

        // 没有相关的评论和回复
        if (!count($comment['records'])) {

            $result = [
                'records' => [],
                'commented' => 0,
                'hasMoreComments' => false
            ];

            return $result;
        }

        // 生成 获取回复的 query
        $replyQuery = $this->leftJoin('user as u', 'u.id', 'comment.user_id')
            ->select(['comment.id', 'level', 'liked', 'reply_to_name', 'user_id', 'name', 'content', 'commentable_id', 'root_id', 'reply_to']);
        
        // 将评论的回复信息插入到评论数据中
        foreach ($comment['records'] as $k => $v) {
            
            // 从redis中 获取被评论的次数
            $todayCommentNum = RedisCache::getCommentCommented($comment['records'][$k]['id']);

            if ($todayCommentNum) {
                $comment['records'][$k]['commented'] += $todayCommentNum;
            }

            // 如果 该评论没有相关的回复
            if (!$v['commented'] && !$todayCommentNum) {
                $comment['records'][$k]['replies'] = [];
                continue;
            }

            $replyTmpQuery = clone $replyQuery;
            
            $replies = $replyTmpQuery
                ->where('root_id', $v['id'])
                ->where('type', '!=', 1)
                ->limit(3)
                ->get()
                ->toArray();

            $comment['records'][$k]['replies'] = $replies;
        }

        $result = ['records' => $comment['records']];


        // 获取相关模型被评论的次数
        if ($commentableType === 'Blog1997') {
            $result['commented'] = $this->select(DB::raw('count(id) as total'))->where('commentable_id', 0)->first()->total;
        } else {
            $result['commented'] = Article::where('id', $commentableId)->select('commented')->first()->commented;
        }

        // 判断是否有更多的记录
        $result['hasMoreComments'] = $comment['pagination']['curPage'] < $comment['pagination']['last'];

        return $result;
    }

    /**
     * 获取评论的回复
     * @param $commentPath
     * @param $offset
     * @return Array
     */
    public function getReply($rootId, $offset)
    {

        $comment = $this->leftJoin('user as u', 'u.id', 'comment.user_id')
            ->select(['comment.id', 'user_id', 'name', 'liked', 'reply_to_name', 'content', 'commentable_id', 'commented', 'root_id', 'level'])
            ->where('root_id', $rootId)
            ->where('level', '!=', '1')
            ->limit(10)
            ->offset($offset)
            ->get();

        return $comment->toArray();
    }
}
