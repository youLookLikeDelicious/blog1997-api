<?php

namespace App\Observers;

use App\Model\Comment;
use App\Facades\RedisCache;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param Comment $comment
     * @return bool
     */
    public function created(Comment $comment)
    {
        // 评论的为文章，将文章|评论的评论数+1
        $level = $comment->level;

        // 回复文章 | 博客留言
        if ($level == 1) {

            $commentableId = $comment->commentable_id;
            
            if ($comment->commentable_type === 'Blog1997') {
                // 评论的是留言内容
                RedisCache::incrSiteCommented();
            } else {
                // 评论的是文章
                RedisCache::incrArticleCommented($commentableId);
            }
        } else if ($level > 1) {
            // 回复评论
            // 为评论的回复数+1
            RedisCache::incrCommentCommented($comment->root_id);

            // 获取文章id
            $parentCommentableId = Comment::select('commentable_id')->where('id', $comment->root_id)->first()->commentable_id;

            if (!$parentCommentableId) {
                // 如果一级评论 是博客的留言
                RedisCache::incrSiteCommented();
            } else {
                // 为文章的回复数+1
                RedisCache::incrArticleCommented($parentCommentableId);
            }
        } else{
            return false;
        }
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param Comment $comment
     * @return bool
     */
    public function deleted(Comment $comment)
    {
        $level = $comment->level;

        switch ($level) {
            // 删除一级评论
            case 1:
                $this->deleteCommentOfLevelOne($comment);
                break;
            // 删除二级评论
            case 2:
            // 删除三级评论
            case 3:
                $this->deleteCommentOfLevelTwo($comment);
            break;
            default:
                return false;
        }
    }

    /**
     * 删除一级评论下的回复
     * @param $comment
     */
    protected function deleteCommentOfLevelOne ($comment)
    {
        Comment::where('root_id', $comment->id)
            ->delete();
    }

    /**
     * 删除二级、三级评论
     * @param $comment
     */
    protected function deleteCommentOfLevelTwo ($comment)
    {
        // 获取顶级评论
        $topComment = Comment::select(['commentable_id', 'commentable_type'])
            ->where('id', $comment->root_id)
            ->first();

        if ($topComment['commentable_type'] === 'Blog1997') {
            // 如果一级评论是 网站的留言
            RedisCache::decrSiteCommented();
        } else {
            // 如果一级评论是文章的 回复
            RedisCache::decrArticleCommented($topComment->commentable_id);
        }
        
        RedisCache::decrCommentCommented($comment->root_id);
    }
}
