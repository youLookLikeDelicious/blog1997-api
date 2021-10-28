<?php

namespace App\Events;

use App\Model\Gallery;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class GalleryCreateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Gallery Eloquent
     *
     * @var Gallery
     */
    public $gallery;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Gallery $gallery)
    {
        $this->gallery = $gallery;
    }
}
