<?php

namespace App\Http\Controllers\Home;

use App\Model\Article;
use App\Model\FriendLink;
use App\Facades\RedisCache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * 获取首页相关信息
     * @param Request $request
     * @param Article $article
     * @return mixed
     */
    public function index(Request $request, Article $article) {

        // 获取最新的文章
        $newArticle = $article->relations()
            ->selectRaw('to_base64(id) as id, title, is_origin, user_id, content as summary, visited, gallery_id, commented, created_at, updated_at, keywords')
            ->orderBy('created_at', 'DESC')
            ->orderBy('updated_at', 'DESC')
            ->first();

        // 最新文章的摘要最大长度设为 700
        $summary = preg_replace('/<[^>]*>/', '', $newArticle->summary);
        $newArticle->summary = mb_substr($summary, 0, 700);

        // 获取最新文章的id
        $newArticleId = base64_decode($newArticle->id) + 0;
        $articles = $article->getArticleList("id != {$newArticleId}");

        // 将记录列表转为数组
        $articles['records'] = $articles['records']->toArray();

        // 获取留言的数量
        $messageNum = RedisCache::getSiteLeaveMessageNum();

        // 获取本月点击TOP 7
        $popArticle = $article->selectRaw('to_base64(id) as id, title, visited, created_at')
            ->orderBy('visited', 'DESC')
            ->limit(7)
            ->get();

        // 将最新的文章追加到列表栈顶
        array_shift($articles['records']);
        array_unshift($articles['records'], $newArticle);

        // 生成返回的结果
        $result = [
            'articles' => $articles['records'],
            'pages' => $articles['pagination']['pages'],
            'articleNum' => $articles['pagination']['total'],
            'messageNum' => $messageNum,
            'popArticles' => $popArticle
        ];

        return response()->success($result);
    }

    /**
     * 获取友链列表
     * @param FriendLink $friendLink 友链模型
     */
    public function getFriendLink (FriendLink $friendLink) {

        $friendLink = $friendLink->select(['id', 'name', 'url'])->get();
        return response()->success($friendLink);
    }
}
