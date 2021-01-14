<?php

namespace App\Listeners;

use App\Model\Article;
use App\Events\ApproveIllegalInfoEvent;
use Illuminate\Queue\InteractsWithQueue;
use App\Contract\Repository\ArticleBackUp;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApproveIllegalArticleListener
{
    /**
     * Article Backup repository
     *
     * @var App\Contract\Repository\ArticleBackUp
     */
    protected $articleBackup;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ArticleBackUp $articleBackup)
    {
        $this->articleBackup = $articleBackup;
    }

    /**
     * Handle the event.
     * Remove illegal article records to article_back table
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ApproveIllegalInfoEvent $event)
    {
        $mailbox = $event->mailbox;
        
        // 如果文章已被清理
        if ($mailbox->type === 'App\Model\Article' && !$this->articleBackup->exists($mailbox->reported_id)) {

            $article = Article::findOrFail($mailbox->reported_id);

            $article->delete();
        }
    }
}
