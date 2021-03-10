<?php

namespace App\Listeners;

use App\Events\pushArticleByApi as EventsPushArticleByApi;
use App\Model\Article;
use App\Service\CurlService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class pushArticleByApi implements ShouldQueue
{
    /**
     * Curl service
     *
     * @var CurlService
     */
    protected $curlService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CurlService $curlService)
    {
        $this->curlService = $curlService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EventsPushArticleByApi $event)
    {
        $token = config('app.baiduToken');
        if (!$token) {
            return;
        }

        $articleUrl = rtrim(config('app.url'), '/') . '/article' . '/' . base64_encode($event->article->id);

        $result = $this->curlService->make("http://data.zz.baidu.com/urls?site=https://www.blog1997.com&token={$token}", [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $articleUrl,
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        ]);

        $result = json_decode($result);

        Log::info('文章推送百度成功;' . $articleUrl, [
            'operate' => 'queue',
            'result' => isset($result->success) ? 'success' : 'failure'
        ]);
    }
}
