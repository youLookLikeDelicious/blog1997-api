<?php

namespace App\Schedule;

use App\Facades\CacheModel;
use App\Models\Article;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MigrateArticleCache
{
    protected function migrate()
    {
        // 获取缓存的id列表
        $ids = CacheModel::getCachedIds('article');

        // 没有缓存，不做任何操作
        if (!$ids) {
            return;
        }

        $GLOBALS['startTime'] = microtime(true);

        foreach($ids as $id) {
            $key = "article-{$id}-cache";

            if (!Cache::has($key)) {
                continue;
            }

            // 获取缓存的数据
            $cachedData = Cache::get($key);
            
            $article = Article::find($id);
            $article->timestamps = false;
            
            foreach($cachedData as $key => $v) {
                $article->increment($key, $v);
            }
            
            CacheModel::forget('article', $id);
        }

        Log::info('文章缓存迁移成功 +' . count($ids), ['operate' => 'schedule']);
    }

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
        $this->migrate();
    }
}
