<?php

namespace App\Listeners;

use App\Events\ThumbUpEvent;
use App\Facades\CacheModel;
use App\Models\MessageBox;
use App\Models\ThumbUp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ThumbUpListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ThumbUpEvent $event)
    {
        $thumbUp = $event->thumbUp;

        $thumbUp->load('thumbable:id,title,user_id');

        // 缓存数据
        $this->cacheThumbUp($thumbUp);

        // 生成消息通知
        $this->makeNotification($thumbUp);
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
            case 'comment':
                // 评论点赞+1
                CacheModel::incrementCommentLiked($thumbUp->able_id);
                break;
            case 'article':
                // 文章点赞+1
                CacheModel::incrementArticleLiked($thumbUp->able_id);
                break;
        }
    }

    /**
     * 生成消息通知
     *
     * @param ThumbUp $thumbUp
     * @return void
     */
    protected function makeNotification($thumbUp)
    {
        if ($thumbUp->thumbable->user_id === auth()->id()) {
            return;
        }

        MessageBox::firstOrCreate([
            'sender' => $thumbUp->user_id,
            'type' => 'thumbup',
            'reported_id' => $thumbUp->id,
            'receiver' => $thumbUp->thumbable->user_id,
            'content' => $thumbUp->able_type === 'article' ? '赞了你的文章' : '赞了你的评论'
        ]);
    }
}
