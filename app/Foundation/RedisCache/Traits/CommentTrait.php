<?php
namespace App\Foundation\RedisCache\Traits;

use Illuminate\Support\Facades\Redis;

trait CommentTrait {

    /**
     * 获取评论被回复的次数
     * @param $id
     * @return mixed
     */
    public function getCommentCommented ($id) {

        $todayData = Redis::hget("comment-{$this->date}_{$id}", 'commented');

        $yesterdayData = Redis::hget("comment-{$this->yesterdayDate}_{$id}", 'commented');

        $sum = 0;
        
        if ($todayData) {
            $sum += $todayData;
        }

        if ($yesterdayData) {
            $sum += $yesterdayData;
        }

        return $sum;
    }

    /**
     * 评论的回复数+1
     * @param $id
     * @return integer
     */
    public function incrCommentCommented ($id) {
        return Redis::hincrby("comment-{$this->date}_{$id}", 'commented', 1);
    }

    /**
     * 被回复次数-n
     * @param $id
     * @param int $num
     * @return integer
     */
    public function decrCommentCommented ($id, $num = 1) {
        return Redis::hincrby("comment-{$this->date}_{$id}", 'commented', -$num);
    }

    public function incrCommentLiked ($id) {
        return Redis::hincrby("comment-{$this->date}_{$id}", 'liked', 1);
    }

    /**
     * 当天文章的点赞数-1
     * @param $id
     * @return integer
     */
    public function decrCommentLiked ($id) {
        return Redis::hincrby("comment-{$this->date}_{$id}", 'liked', -1);
    }
}
