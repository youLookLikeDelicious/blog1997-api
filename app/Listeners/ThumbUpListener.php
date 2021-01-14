<?php

namespace App\Listeners;

use App\Events\ThumbUpEvent;
use App\Model\MessageBox;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ThumbUpListener
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
        
        if ($thumbUp->thumbable->user_id === auth()->id()) {
            return;
        }

        MessageBox::firstOrCreate([
            'sender' => $thumbUp->user_id,
            'type' => get_class($thumbUp),
            'reported_id' => $thumbUp->id,
            'receiver' => $thumbUp->thumbable->user_id,
            'content' => $thumbUp->able_type === 'App\Model\Article' ? '赞了你的文章' : '赞了你的评论'
        ]);
    }
}
