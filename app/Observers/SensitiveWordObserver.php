<?php

namespace App\Observers;

use App\Model\SensitiveWord;
use Illuminate\Support\Facades\Log;
use App\Model\SensitiveWordCategory;

class SensitiveWordObserver
{
    /**
     * Handle the sensitive "created" event.
     * 更新分类的计数
     *
     * @param  \App\Model\SensitiveCategory  $sensitiveCategory
     * @return void
     */
    public function created(SensitiveWord $sensitiveWord)
    {
        SensitiveWordCategory::where('id', $sensitiveWord->category_id)
            ->increment('count');

        Log::info('敏感词添加成功', ['operate' => 'create', 'result'=> 'success']);
    }

    /**
     * Handle sensitive word deleted event
     *
     * @param SensitiveWord $sensitiveWord
     * @return void
     */
    public function deleted(SensitiveWord $sensitiveWord)
    {
        SensitiveWordCategory::where('id', $sensitiveWord->category_id)
            ->decrement('count');
    }
}
