<?php

namespace App\Events;

use App\Model\MessageBox;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ApproveIllegalInfoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 邮箱模型
     *
     * @var App\Model\MessageBox
     */
    public $mailbox;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MessageBox $mailbox)
    {
        $this->mailbox = $mailbox;
    }
}
