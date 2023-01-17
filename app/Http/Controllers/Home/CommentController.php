<?php

namespace App\Http\Controllers\Home;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CommentPost;
use App\Http\Controllers\Controller;
use App\Contract\Repository\Comment as CommentRepository;

/**
 * @group Comment management
 * @authenticated
 * 
 * 评论管理
 */
class CommentController extends Controller
{
    /**
     * Store newly create comment
     * 
     * 添加评论
     * 
     * @bodyParam able_id   mixed  required   被评论记录的id
     * @bodyParam able_type string required   被评论记录的类型
     * @bodyParam content   string required   评论的内容
     * @responseFile response/home/comment/create.json
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store (CommentPost $request)
    {
        $data = $request->validated();

        $newComment = DB::transaction(function () use ($data) {

            $newComment = Comment::create($data);
            
            $newComment['replies'] = [];
            $newComment['liked'] = 0;

            return $newComment;
        });
        
        $newComment->load(['user:id,name,avatar', 'receiver:id,name,avatar']);
        $newComment->makeHidden(['verified', 'article_id', 'able_type']);
        
        return response()->success($newComment, '评论成功');
    }

    /**
     * Get reply of specific comment
     * 
     * 获取评论的回复
     * 
     * @urlParam $rootId 评论的root_id
     * @urlParam $offset 记录的起始位置
     * @responseFile response/home/comment/destroy.json
     * @param int $rootId
     * @param int $offset
     * @return \Illuminate\Http\Response
     */
    public function getReply (CommentRepository $comment, $rootId, int $offset = 0)
    {
        $comment = $comment->getReply($rootId, $offset);

        return response()->success($comment);
    }

    /**
     * Destroy specific comment
     * 
     * 删除评论
     * 同时也会删除相关的回复内容
     * 
     * @urlParam comment required 评论的id
     * @responseFile response/home/comment/get-reply.json
     * @param Request $request
     * @return \Illuminate\Http\Response
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
