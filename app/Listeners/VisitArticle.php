<?php

namespace App\Listeners;

use App\Events\VisitArticle as Event;
use App\Facades\CacheModel;

class VisitArticle
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\VisitArticle  $event
     * 
     * @return void
     */
    public function handle(Event $event)
    {
        $articleId = $event->id;
        // 生成键
        $key = $this->getKeyOfSession($articleId);

        $ids = session('article');

        if ($ids && array_key_exists($articleId, $ids) && $ids[$articleId][0] + 60 * 60 * 60 > time()) {
            return;
        }

        session()->push($key, time());

        // 如果当前用户在指定时间内，没有阅读该文章
        // 1、文章阅读量数量 +1
        // 2、将id保存在session里面
        CacheModel::incrementArticleVisited($articleId);
    }

    /**
     * 获取session的键值
     *
     * @param int $articleId
     * @return string
     */
    protected function getKeyOfSession($articleId)
    {
        return 'article.' . $articleId;
    }
}
