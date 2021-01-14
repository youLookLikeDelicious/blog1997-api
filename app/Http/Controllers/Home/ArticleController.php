<?php

namespace App\Http\Controllers\Home;

use App\Events\VisitArticle;
use Illuminate\Http\Request;
use App\Contract\Repository\Article;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * repository
     *
     * @var \App\Contract\Repository\Article
     */
    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * 获取文章详情 以及相关的回复
     * Method GET
     * 
     * @return Illuminate\Http\Response
     */
    public function find ($articleId)
    {
        $articleRecord = $this->article->find($articleId);

        return response()->success($articleRecord);
    }

    /**
     * 获取文章的相关评论
     *
     * @param string $articleId
     * @return \Illuminate\Http\Response
     */
    public function comments($articleId)
    {
        $comments = $this->article->comments($articleId);

        event(new VisitArticle($comments['articleId']));

        unset($comments['articleId']);

        return response()->success($comments);
    }

    /**
     * 获取文章列表
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function all (Request $request)
    {
        $articles = $this->article->all($request);

        return response()->success($articles);
    }

    /**
     * 通过标签，对文章分类
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function tags(Request $request)
    {
        $data = $this->article->tags($request);

        return response()->success($data);
    }
}
