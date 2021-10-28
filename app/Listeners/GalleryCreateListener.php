<?php

namespace App\Listeners;

use App\Service\GalleryService;
use App\Events\GalleryCreateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class GalleryCreateListener
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
            ->createTinyThumbnail($gallery->getAttributes()['url']);

        if ($base64Image) {
            $gallery->update([
                'thumbnail' => $base64Image
            ]);
        }
    }
}
