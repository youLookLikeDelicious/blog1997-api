<?php

namespace App\Http\Controllers\Home;

use App\Events\VisitArticle;
use Illuminate\Http\Request;
use App\Contract\Repository\Article;
use App\Http\Controllers\Controller;

/**
 * @group Article management
 * 
 * 文章管理
 */
class ArticleController extends Controller
{
    /**
     * Article repository
     *
     * @var \App\Contract\Repository\Article
     */
    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Show the specific article in front page
     * 获取文章详情
     * 
     * @urlParam articleId required The id of article 
     * @responseFile response/home/article/find.json
     * @param $articleId 文章id
     * @return \Illuminate\Http\Response
     */
    public function find ($articleId)
    {
        $articleRecord = $this->article->find($articleId);

        event(new VisitArticle($this->article->decodeArticleId($articleId)));

        return response()->success($articleRecord);
    }

    /**
     * Get comments about the specific article
     * 
     * 获取文章的相关评论
     * 并将文章的访问量+1
     *
     * @urlParam articleId required The id of article
     * @queryParam p 请求的页数 默认是1
     * @responseFile response/home/article/comments.json
     * @param $articleId 文章id
     * @return \Illuminate\Http\Response
     */
    public function comments($articleId)
    {
        $comments = $this->article->comments($articleId);

        return $comments->response();
    }

    /**
     * Search article
     * 
     * 搜索文章
     * 
     * @queryParam order_by 排序的方式
     * @queryParam tag_id   标签ID
     * @queryParam keyword  关键字
     * @queryParam p        请求的页数 默认是1
     * @responseFile response/home/article/all.json
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function all (Request $request)
    {
        $resource = $this->article->all($request);

        return $resource->response();
    }

    /**
     * Search article by tags
     * 
     * 根据标签检索文章
     *
     * @queryParam tag_id 标签id
     * @queryParam p      请求的页数 默认是1
     * @responseFile response/home/article/tags.json
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function tags(Request $request)
    {
        $data = $this->article->tags($request);

        return $data->response();
    }
}
