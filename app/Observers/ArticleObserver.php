<?php

namespace App\Observers;

use App\Model\Article;
use App\Model\Topic;
class ArticleObserver
{
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
        $imgUrl = array_column($imgUrl, 1);
        $originImgUrl = array_column($originImgUrl, 1);
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
     * 获取富文本图片的src属性
     * @param $content
     * @return array
     */
    protected function getImgUrl ($content) {
        $pattern = $pattern = "/<img.*?src=\"([\w\.\-\/:]*?)\".*?>/";
        preg_match_all($pattern, $content, $imgUrl, PREG_SET_ORDER);

        return $imgUrl;
    }

    /**
     * 从服务器上将图片删除
     * @param Array $imgList 图片列表
     * @return void
     */
    protected function unlinkImage ($imgList) {
        foreach ($imgList as $v) {
            $filePath = storage_path(strstr($v, 'image'));
            if (is_file($filePath)) {
                unlink($filePath);
            }

            // 删除webp格式的文件
            $filePath = str_replace(strrchr($filePath, '.'), '', $filePath) . '.webp';
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
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
