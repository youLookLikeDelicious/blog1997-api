<?php
namespace App\Foundation\Traits\CacheModel;

trait CacheComment
{
    /**
     * 评论的点赞数量 + 1
     *
     * @param int $id
     * @return int
     */
    public function incrementCommentLiked($id)
    {
        return $this->cache('comment', 'liked', $id);
    }

    /**
     * 评论的回复数量 +1
     *
     * @param int $id
     * @return int
     */
    public function incrementCommentCommented($id)
    {
        return $this->cache('comment', 'commented', $id);
    }


    /**
     * 评论的回复数量 -1
     *
     * @param int $id
     * @return int
     */
    public function decrementCommentCommented($id, $count = 1)
    {
        return $this->cache('comment', 'commented', $id, -$count);
    }

    /**
     * 获取评论的点赞数量
     *
     * @param int $id
     * @return int
     */
    public function getCommentLiked($id)
    {
        return $this->get('comment', 'liked', $id);
    }

    /**
     * 获取评论的回复数量
     *
     * @param int $id
     * @return int
     */
    public function getCommentCommented($id)
    {
        return $this->get('comment', 'commented', $id);
    }

    /**
     * 网站留言的数量 -n
     *
     * @return int
     */
    public function decrementLeaveMessageCommented($count = 1)
    {
        return $this->cache('site', 'commented', -$count);
    }
}
