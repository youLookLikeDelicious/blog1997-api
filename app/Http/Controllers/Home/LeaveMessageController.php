<?php

namespace App\Http\Controllers\Home;

use App\Repository\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveMessageController extends Controller
{
    /**
     * 获取网站的留言
     * 
     * @param Request $request
     * @param App\Model\Comment $comment
     * @return mixed
     */
    public function index (Comment $comment) {
        // 获取留言
        $comments = $comment->getLeaveMessage();

        return response()->success($comments);
    }
}
