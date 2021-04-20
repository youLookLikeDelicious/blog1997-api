<?php

namespace App\Http\Controllers\Home;

use App\Repository\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @group Leave message management
 * 
 * 获取留言信息
 */
class LeaveMessageController extends Controller
{
    /**
     * Get leave message
     * 
     * 获取网站的留言
     * 
     * @responseFile response/home/leave-message/index.json
     * @param App\Model\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function index (Comment $comment)
    {
        $comments = $comment->getLeaveMessage();

        return response()->success($comments);
    }
}
