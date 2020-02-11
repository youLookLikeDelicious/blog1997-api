<?php

namespace App\Http\Controllers\Home;

use App\Model\Comment;
use App\Facades\RedisCache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveMessageController extends Controller
{
    /**
     * 获取网站的留言
     * @param Request $request
     * @return mixed
     * @return mixed
     */
    public function index (Request $request, Comment $comment) {
        // 获取留言
        $comments = $comment->getComment(0, 'Blog1997');

        // 获取留言的数量
        $commentNum = RedisCache::getSiteLeaveMessageNum() + 0;

        $result = array_merge($comments, ['commented' => $commentNum]);

        return response()->success($result);
    }
}
