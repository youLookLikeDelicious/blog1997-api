<?php

namespace App\Observers;

use App\Events\ThumbUpEvent;
use App\Model\Article;
use App\Model\Comment;
use App\Model\ThumbUp;
use App\Facades\CacheModel;
use Illuminate\Support\Facades\Log;

class ThumbUpObserver
{
    /**
     * Handle the thumb up "created" event.
     *
     * @param  \App\Model\ThumbUp  $thumbUp
     * @return void
     */
    public function created(ThumbUp $thumbUp)
    {
        $this->cacheThumbUp($thumbUp);

        event(new ThumbUpEvent($thumbUp));

        Log::info('点赞成功', ['operate' => 'create', 'result' => 'success']);
    }

    /**
     * Handle the thumb up "updated" event.
     *
     * @param  \App\Model\ThumbUp  $thumbUp
     * @return void
     */
    public function updated(ThumbUp $thumbUp)
    {
        if ($thumbUp->isDirty('content')) {
            $this->cacheThumbUp($thumbUp);
        }
    }

    /**
     * Cache Thumb up information to redis
     *
     * @param ThumbUp $thumbUp
     * @return void
     */
    protected function cacheThumbUp(ThumbUp $thumbUp)
    {
        switch ($thumbUp->able_type) {
            case Comment::class:
                // 评论点赞+1
                CacheModel::incrementCommentLiked($thumbUp->able_id);
                break;
            case Article::class:
                // 文章点赞+1
                CacheModel::incrementArticleLiked($thumbUp->able_id);
                break;
        }
    }
}
