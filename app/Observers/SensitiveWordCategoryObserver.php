<?php

namespace App\Observers;

use App\Models\SensitiveWord;
use Illuminate\Support\Facades\Log;
use App\Models\SensitiveWordCategory;

class SensitiveWordCategoryObserver
{
    /**
     * Handle the sensitive category "deleted" event.
     * 删除分类后，删除该分类下的关键词
     *
     * @param  \App\Models\SensitiveCategory  $sensitiveCategory
     * @return void
     */
    public function deleted(SensitiveWordCategory $sensitiveCategory)
    {
        SensitiveWord::where('category_id', $sensitiveCategory->id)->delete();

        Log::info('敏感词汇分类，以及分类下的敏感词删除成功', ['operate' => 'delete', 'result'=> 'success']);
    }
}
