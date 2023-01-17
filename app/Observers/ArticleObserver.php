<?php

namespace App\Observers;

use App\Events\pushArticleByApi;
use App\Models\User;
use App\Models\Topic;
use App\Models\Article;
use App\Models\ArticleBackUp;
use App\Events\UpdateArticleEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ArticleObserver
{
    /**
     * Handle the article "creating" event.
     *
     * @param  \App\Models\Article  $article
     * @return void
     */
    public function creating(Article $article)
    {
        if ($article->isDraft()) {
            Log::info('草稿保存成功', ['operate' => 'create', 'result' => 'success']);
            return;
        }
        
        $this->incrementCounter($article);

        Log::info('文章创建成功', ['operate' => 'create', 'result' => 'success']);
    }

    /**
     * Handle the article "created" event.
     *
     * @param  \App\Models\Article  $article
     * @return void
     */
    public function created(Article $article)
    {
        if ($article->isDraft()) {
            Article::where('id', $article->id)->limit(1)->update([
                'article_id' => $article->id
            ]);
        } else {
            event(new pushArticleByApi($article));
        }
    }

    /**
     * Handle the article "updating" event.
     *
     * @param  \App\Models\Article  $article
     * @return void
     */
    public function updating(Article $article)
    {
        $message = '文章保存成功';

        // 如果草稿没有对应的原文,不进行删除
        if (!(!$article->isDirty('is_draft') && $article->isDraft())) {
            $this->deleteDraft($article);
        }

        if ($article->isDirty('is_draft')) {
            // 将已有的文章，保存为草稿
            // 删除该文章对应的草稿
            // 创建一个草稿
            if ($article->isDraft()) {
                $message = '草稿保存成功';
                $this->createDraft($article);
                return false;
            }

            // 将草稿发布为文章
            // 删除文章以及对应的草稿
            $message = '草稿发布成功';
            $this->deleteArticle($article);
            $newArticle = $article->toArray();
            Article::insert(array_merge($newArticle, ['id' => $article->article_id]));
            
            if ($article->id === $article->article_id + 0) {
                $this->incrementCounter($article);
            }
        }

        // 如果更改了content字段，判断图片是否有删除，如果有，从硬盘中删除图片
        if ($article->isDirty('content')) {
            event(new UpdateArticleEvent($article));
        }

        // 清除缓存
        Cache::forget('article-' . $article->id);
        
        Log::info($message, ['operate' => 'update', 'result' => 'success']);        
    }

    /**
     * Handle the article "deleting" event.
     *
     * @param  \App\Models\Article  $article
     * @return void
     */
    public function deleting(Article $article)
    {
        // 专题数 -1 
        $topicId = $article->topic_id;

        $article->delete_role = 'user';

        Topic::where('id', $topicId)->decrement('article_sum');

        User::find(Auth::id())->decrement('article_sum');

        // 删除草稿
        Article::draft($article->id)->delete();

        // 将文章移到备份表中
        $article->deleted_at = time();

        ArticleBackUp::create($article->makeHidden(['is_draft', 'article_id'])->toArray());

        Log::info('文章删除成功', ['operate' => 'delete', 'result' => 'success']);
    }

    /**
     * 更新草稿时,删除之前的草稿
     *
     * @param Article $article
     * @return void
     */
    protected function deleteDraft(Article $article)
    {
        Article::where('user_id', auth()->id())
            ->where('article_id', $article->article_id ?: $article->id)
            ->where('is_draft', 'yes')
            ->delete();
    }

    /**
     * 删除文章的原稿
     *
     * @param Article $article
     * @return void
     */
    protected function deleteArticle($article)
    {
        Article::where('id', $article->article_id)
            ->limit(1)
            ->delete();
    }

    /**
     * 创建文章后，相关的计数器 + 1
     *
     * @param Article $article
     * @return void
     */
    protected function incrementCounter($article)
    {
        Topic::where('id', $article->topic_id)->increment('article_sum');

        User::find(Auth::id())->increment('article_sum');
    }

    /**
     * Create a draft for article
     *
     * @param Article $article
     * @return void
     */
    public function createDraft(Article $article)
    {
        $draftArticle = $article->toArray();

        $draftArticle['created_at'] = Carbon::parse($draftArticle['created_at'])->getTimestamp();
        $draftArticle['updated_at'] = Carbon::parse($draftArticle['updated_at'])->getTimestamp();
        $draftArticle['article_id'] = $article->id;

        unset($draftArticle['id']);

        Article::insert($draftArticle);
    }
}
