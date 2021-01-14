<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Model\SensitiveWordCategory;
use App\Events\SensitiveWordBatchInsert as Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SensitiveWordBatchInsert
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
    public function handle(Event $event)
    {
        SensitiveWordCategory::where('id', $event->category_id)
            ->increment('count', $event->count);

        Log::info('敏感词汇批量导入成功', ['operate' => 'update', 'result'=> 'success']);
    }
}
