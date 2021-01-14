<?php

namespace App\Http\Controllers\Home;

use App\Model\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CommentPost;
use App\Http\Controllers\Controller;
use App\Contract\Repository\Comment as CommentRepository;


class CommentController extends Controller
{
    /**
     * 上传评论
     * Method POST
     * 
     * @param Request $request
     * @return mixed
     */
    public function store (CommentPost $request)
    {
        // 验证字段
        $data = $request->validated();

        $newComment = '';
        
        DB::transaction(function () use ($data, &$newComment) {
            $newComment = Comment::create($data);
            $newComment['replies'] = [];
        });
        
        $newComment->load(['user:id,name,avatar', 'receiver:id,name,avatar']);
        $newComment->makeHidden(['verified', 'article_id', 'able_type']);
        
        return response()->success($newComment, '评论成功');
    }

    /**
     * 获取回复
     * Method GET
     * 
     * @param int $rootId
     * @param int $offset
     * @return Response
     */
    public function getReply (CommentRepository $comment, $rootId, $offset)
    {

        $comment = $comment->getReply($rootId, $offset);

        return response()->success($comment);
    }

    /**
     * 删除评论以及相关回复
     * 
     * Method POST
     * @param Request $request
     * @return Response
     */
    public function destroy (Comment $comment)
    {
        // 获取被删除的记录信息
        // root_id 字段在observer中使用

        $data = [];
        DB::transaction(function () use ($comment, &$data) {
            
            $comment->delete();

            // 生成返回的数据
            $data = [
                'rows' => $comment->deleteCommentedNum
            ];
            
        });

        return response()->success($data, '删除评论成功');
    }
}
