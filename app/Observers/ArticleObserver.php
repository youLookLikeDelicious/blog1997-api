<?php

namespace App\Observers;

use App\Model\Topic;
use App\Model\Article;
use App\Traits\ArticleObserver\deleteImage;

class ArticleObserver
{
    use deleteImage;

    /**
     * Handle the article "created" event.
     *
     * @param  \App\Model\Article  $article
     * @return void
     */
    public function created(Article $article)
    {
        // 获取专题di
        $topicId = $article->topic_id;
        Topic::where('id', $topicId)->increment('article_sum');
    }

    /**
     * Handle the article "updating" event.
     *
     * @param  \App\Model\Article  $article
     * @return void
     */
    public function updating(Article $article)
    {
        // 如果更改了content字段，判断图片是否有删除，如果有，从硬盘中删除图片
        if (!$article->isDirty('content')) {
            return true;
        }

        // 获取图片的url
        $originImgUrl = $this->getImgUrl($article->getOriginal('content'));
        $imgUrl = $this->getImgUrl($article->content);

        // 计算出不同的url
        $result = array_diff($originImgUrl, $imgUrl);

        // 删除图片
        $this->unlinkImage($result);
    }

    /**
     * Handle the article "deleting" event.
     *
     * @param  \App\Model\Article  $article
     * @return void
     */
    public function deleting(Article $article)
    {
        // 获取专题id
        $topicId = $article->topic_id;
        Topic::where('id', $topicId)->decrement('article_sum');

        // 获取文章图片地址
        $imgList = $this->getImgUrl($article->content);
        $this->unlinkImage($imgList);
    }

    /**
     * Handle the article "restored" event.
     *
     * @param  \App\Model\Article  $article
     * @return void
     */
    /*public function restored(Article $article)
    {
        //
    }*/

    /**
     * Handle the article "force deleted" event.
     *
     * @param  \App\Model\Article  $article
     * @return void
     */
    /*public function forceDeleted(Article $article)
    {

    }*/
}
