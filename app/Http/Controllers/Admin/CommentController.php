<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BatchRequest;
use App\Http\Controllers\Controller;
use App\Contract\Repository\Comment as RepositoryComment;

/**
 * @group Comment management
 * 
 * 评论管理
 */
class CommentController extends Controller
{
    /**
     * Get unverified comments
     * 
     * 获取未审核的所有评论
     * Display a listing of the resource.
     * 
     * @responseFile response/admin/comment/index.json
     * 
     * @param Request $request
     * @param RepositoryComment $repository
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, RepositoryComment $repository)
    {
        $comments = $repository->getUnVerified($request);

        return $comments->toResponse($request);
    }

    /**
     * Approve submit comment
     * 
     * 评论通过审核
     * Set comment be verified .
     * 
     * @bodyParam ids   array required 评论id列表
     * @bodyParam ids.* int required 评论id
     * @responseFile response/admin/comment/approve.json
     *
     * @return \Illuminate\Http\Response
     */
    public function approve(BatchRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            foreach ($data['ids'] as $id) {
                $comment = Comment::select('id', 'verified', 'able_type', 'able_id', 'level', 'root_id')->find($id);
                $comment->update(['verified' => 'yes']);
            }
        });

        $count = count($data['ids']);
        return response()->success('', "操作成功,{$count}条记录通过审批");
    }

    /**
     * Reject submit comment
     * 
     * 回绝评论,不予以显示该评论
     * Batch reject comments
     * 
     * @bodyParam ids   array required 评论id列表
     * @bodyParam ids.* int required 评论id
     * @responseFile response/admin/comment/reject.json
     * 
     * @param BatchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function reject(BatchRequest $request, RepositoryComment $repository)
    {
        $ids = $request->validated()['ids'];

        DB::transaction(function () use ($ids, $repository) {
            foreach ($ids as $id) {
                $repository->find($id)->delete();
            }
        });

        $count = count($ids);
        return response()->success('', "操作成功,{$count}条记录被移除");
    }
}
