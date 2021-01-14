<?php

namespace App\Listeners;

use App\Events\ArticleBackUpDeleted;
use App\Model\Comment;
use App\Service\FilterStringService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ArticleBackUpDeletedListener
{
    /**
     * Use filter String service to extract image url
     *
     * @var FilterStringService
     */
    protected $filterStringService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(FilterStringService $filterStringService)
    {
        $this->filterStringService = $filterStringService;
    }

    /**
     * Handle the event.
     *
     * @param  ArticleBackUpDeleted  $event
     * @return void
     */
    public function handle(ArticleBackUpDeleted $event)
    {
        $article = $event->article;

        // 删除上传的图片
        $imageUrls = $this->filterStringService
            ->extractImagUrl($article->content);
            
        Storage::delete($imageUrls);

        // 取消和tag的多对多关系
        $article->tags()->sync([]);

        // 删除相关的评论
        Comment::where('article_id', $article->id)->delete();
    }
}
