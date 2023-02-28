<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Contract\Repository\Article;
use App\Http\Controllers\Controller;
use App\Contract\Repository\Comment;
use App\Contract\Repository\FriendLink;
use App\Models\Tag;
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
        $seconds = getCacheSeconds(2 * 60 * 60 + mt_rand(0, 100));

        $resource = Cache::remember('home.list', $seconds, function () use ($request, $article, $comment) {
            $resource = $article->all($request);
            $resource->additional([
                'popArticles' => $article->getTopTen(),
                'messageNum'  => $comment->getLeaveMessageCount()
            ]);
            return $resource;
        }); 

        return $resource->response();
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

    /**
     * Aet all tags
     * 获取所有标签
     *
     * @return \Illuminate\Http\Response
     */
    public function tags()
    {
        $tags = Tag::where('user_id', 0)->get();

        return response()->success($tags);
    }
}
