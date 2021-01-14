<?php
namespace App\Foundation\Traits\CacheModel;

trait CacheArticle
{
    /**
     * 文章访问数量+1
     *
     * @param int $id
     * @return int
     */
    public function incrementArticleVisited($id)
    {
        return $this->cache('article', 'visited', $id);
    }
    
    /**
     * 文章点赞数量+1
     *
     * @param int $id
     * @return int
     */
    public function incrementArticleLiked($id)
    {
        return $this->cache('article', 'liked', $id);
    }

    /**
     * 文章评论数量+1
     *
     * @param int $id
     * @return int
     */
    public function incrementArticleCommented($id)
    {
        return $this->cache('article', 'commented', $id);
    }

    /**
     * 文章评论数量 -1
     *
     * @param int $id
     * @return int
     */
    public function decrementArticleCommented($id, $count = 1)
    {
        return $this->cache('article', 'commented', $id, -$count);
    }

    /**
     * 获取文章访问数量
     *
     * @param int $id
     * @return int
     */
    public function getArticleVisited($id)
    {
        return $this->get('article', 'visited', $id);
    }
    
    /**
     * 获取文章点赞数量
     *
     * @param int $id
     * @return int
     */
    public function getArticleLiked($id)
    {
        return $this->get('article', 'liked', $id);
    }

    /**
     * 获取文章评论数量
     *
     * @param int $id
     * @return int
     */
    public function getArticleCommented($id)
    {
        return $this->get('article', 'commented', $id);
    }
}