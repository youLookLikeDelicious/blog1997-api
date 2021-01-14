<?php

namespace App\Events;

use App\Model\Gallery;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GalleryDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Gallery record
     *
     * @var \App\Model\Gallery
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
