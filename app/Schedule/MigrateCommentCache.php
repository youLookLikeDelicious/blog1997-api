<?php
namespace App\Schedule;

use App\Model\Comment;
use App\Facades\CacheModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MigrateCommentCache
{
    protected function migrate()
    {
        $GLOBALS['startTime'] = microtime(true);
        
        // 获取缓存的id列表
        $ids = CacheModel::getCachedIds('comment');

        if (!$ids) {
            return;
        }

        foreach($ids as $id) {
            $key = "comment-{$id}";

            if (!Cache::has($key)) {
                continue;
            }

            // 获取缓存的数据
            $cachedData = Cache::get($key);

            $query = Comment::where('id', $id);

            foreach($cachedData as $key => $v) {
                $query->increment($key, $v);
            }
            
            CacheModel::forget('comment', $id);
        }

        Log::info('评论缓存迁移成功 +' . count($ids), ['operate' => 'schedule']);
    }
    
    public function __invoke()
    {
        // TODO: Implement __invoke() method.
        $this->migrate();
    }
}