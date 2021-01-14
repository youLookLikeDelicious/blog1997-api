<?php

namespace App\Listeners;

use App\Events\UpdateArticleEvent;
use App\Service\FilterStringService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

/**
 * 更新文章后，尝试在服务器中移除被删除的图片
 */
class UpdateArticleListener implements ShouldQueue
{
    protected $filterStringService;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;
    
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
     * 删除更新后，移除的图片
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UpdateArticleEvent $event)
    {
        $article = $event->article;
        // 获取图片的url
        $originImgUrl = $this->filterStringService
            ->extractImagUrl($article->getOriginal('content'));

        $currentImgUrl = $this->filterStringService
            ->extractImagUrl($article->content);

        $comparedUrl = array_diff($originImgUrl, $currentImgUrl);

        // 生成webp图片的路径
        $comparedUrlForWebp = array_map(function ($url) {
            return str_replace(strrchr($url, '.'), '', $url) . '.webp';
        }, $comparedUrl);

        Storage::delete(array_merge($comparedUrl, $comparedUrlForWebp));
    }
}
