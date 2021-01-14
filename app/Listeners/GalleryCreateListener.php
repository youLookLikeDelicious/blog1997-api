<?php

namespace App\Listeners;

use App\Events\GalleryCreateEvent;
use App\Facades\Upload;
use App\Service\GalleryService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class GalleryCreateListener implements ShouldQueue
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
     * Create tiny thumbnail for gallery image
     *
     * @param  GalleryCreateEvent  $event
     * @return void
     */
    public function handle(GalleryCreateEvent $event)
    {        
        $gallery = $event->gallery;

        $base64Image = app()->make(GalleryService::class)
            ->createTinyThumbnail($gallery->url);

        if ($base64Image) {
            $gallery->update([
                'thumbnail' => $base64Image
            ]);
        }
    }
}
