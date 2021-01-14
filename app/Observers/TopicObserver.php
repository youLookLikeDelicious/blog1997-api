<?php

namespace App\Observers;

use App\Model\Article;
use App\Model\Topic;
use Illuminate\Support\Facades\Log;

class TopicObserver
{
    /**
     * Handle the topic "deleted" event.
     * 删除分类后，删除该分类下的文章
     *
     * @param  \App\Model\SensitiveCategory  $sensitiveCategory
     * @return void
     */
    public function deleted(Topic $topic)
    {
        Article::where('user_id', auth()->id())
            ->where('topic_id', $topic->id)
            ->update(['topic_id' => 0]);

        Log::info('专题删 以及专题下的文章删除成功', ['operate' => 'delete', 'result'=> 'success']);
    }
}
