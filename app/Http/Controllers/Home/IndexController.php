<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Contract\Repository\Article;
use App\Http\Controllers\Controller;
use App\Contract\Repository\Comment;
use App\Contract\Repository\FriendLink;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    /**
     * 获取首页相关信息
     * Method GET
     * 
     * @param Request $request
     * @param Article $article
     * @return mixed
     */
    public function index(Request $request, Article $article, Comment $comment)
    {
        $result = Cache::remember('home.list', (6 * 60 * 60 + mt_rand(0, 500)), function () use ($request, $article) {
            return $article->all($request);
        }); 

        // 获取博客留言的数量
        $messageNum = $comment->getLeaveMessageCount();

        // 获取本月点击TOP 10
        $popArticles = $article->getTopTen();

        $result['popArticles'] = $popArticles;

        $result['messageNum'] = $messageNum;

        return response()->success($result);
    }

    /**
     * 获取友链列表
     * 
     * @param FriendLink $friendLink 友链模型
     */
    public function getFriendLink (FriendLink $friendLink) {

        $result = $friendLink->all();

        return response()->success($result);
    }
}
