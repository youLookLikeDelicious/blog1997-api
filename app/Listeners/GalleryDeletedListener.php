<?php

namespace App\Listeners;

use App\Events\GalleryDeletedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class GalleryDeletedListener
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
    public function handle(GalleryDeletedEvent $event)
    {
        $gallery = $event->gallery;

        // 删除相关的图片
        $url = $gallery->url;

        $extension = strrchr($url, '.');

        $urlWithoutExt = str_replace($extension, '', $url);

        $urls = [
            $url,
            $urlWithoutExt . '.webp',
            $urlWithoutExt . '.min.webp',
            $urlWithoutExt . '.min' . $extension,
        ];

        Storage::delete($urls);
    }
}
