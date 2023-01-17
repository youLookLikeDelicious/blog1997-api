<?php

namespace App\Events;

use App\Models\ThumbUp;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThumbUpEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Thumb up records
     *
     * @var \App\Models\ThumbUp
     */
    public $thumbUp;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ThumbUp $thumbUp)
    {
        $this->thumbUp = $thumbUp;
    }
}
