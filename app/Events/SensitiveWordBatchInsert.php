<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SensitiveWordBatchInsert
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 插入的数量
     *
     * @var int
     */

    public $count;

    /**
     * 分类的id
     *
     * @var int
     */
    public $category_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $count, int $category_id)
    {
        $this->count = $count;
        $this->category_id = $category_id;
    }
}
