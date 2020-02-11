<?php

namespace App\Foundation\RedisCache\Traits;

use App\Schedule\RedisDataToMysql;
use Illuminate\Support\Facades\Redis;

trait ArticleTrait {
    /**
     * 当天文章的点赞数+1
     * @param $id
     * @return integer
     */
    public function incrArticleLiked ($id) {
        return Redis::hincrby("article-{$this->date}_{$id}", 'liked', 1);
    }

    /**
     * 当天文章的点赞数-1
     * @param $id
     * @return integer
     */
    public function decrArticleLiked ($id) {
        return Redis::hincrby("article-{$this->date}_{$id}", 'liked', -1);
    }

    /**
     * 获取当天的点赞数
     * @param $id
     * @return integer
     */
    public function getArticleLiked ($id) {
        $lastData = Redis::hget("article-{$this->yesterdayDate}_{$id}", 'liked');

        $todayData = Redis::hget("article-{$this->date}_{$id}", 'liked');

        $sum = 0;

        if ($lastData) {
            $sum += $lastData;
        }

        if ($todayData) {
            $sum += $todayData;
        }

        return $sum;
    }

    /**
     * 当天文章的访问数+1
     * @param $id
     * @return integer
     */
    public function incrArticleVisited ($id) {

        $result = Redis::hincrby("article-{$this->date}_{$id}", 'visited', 1);

        return $result;
    }

    /**
     * 当天文章的评论数+1
     * @param $id
     * @return integer
     */
    public function incrArticleCommented ($id) {
        return Redis::hincrby("article-{$this->date}_{$id}", 'commented', 1);
    }

    /**
     * 当天文章的评论数-n
     * @param $id
     * @param int $num // 默认 -1
     * @return integer
     */
    public function decrArticleCommented ($id, $num = 1) {
        return Redis::hincrby("article-{$this->date}_{$id}", 'commented', -$num);
    }

    /**
     * 获取文章当天的评论数
     * @param $id
     * @return integer
     */
    public function getArticleCommented ($id) {

        $todayData = Redis::hget("article-{$this->date}_{$id}", 'commented');

        $yesterdayData = Redis::hget("article-{$this->yesterdayDate}_{$id}", 'commented');

        $sum = 0;

        if ($todayData) {
            $sum += $todayData;
        }

        if ($yesterdayData) {
            $sum += $yesterdayData;
        }

        return $sum;
    }
}
