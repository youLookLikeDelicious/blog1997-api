<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Contract\Repository\Article;
use App\Http\Controllers\Controller;
use App\Contract\Repository\Comment;
use App\Contract\Repository\FriendLink;
use Illuminate\Support\Facades\Cache;

/**
 * @group Front index page
 * 
 * 获取前台首页的相关数据
 */
class IndexController extends Controller
{
    /**
     * index
     * 
     * 获取首页相关信息
     * 
     * @responseFile response/home/index/index.json
     * @param Request $request
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Article $article, Comment $comment)
    {
        $result = Cache::remember('home.list', (2 * 60 * 60 + mt_rand(0, 100)), function () use ($request, $article) {
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
     * getFriendLink
     * 获取友链列表
     * 
     * @responseFile response/home/index/friend-link.json
     * @param FriendLink $friendLink 友链模型
     * @return \Illuminate\Http\Response
     */
    public function getFriendLink (FriendLink $friendLink) {

        $result = $friendLink->all();

        return response()->success($result);
    }
}
