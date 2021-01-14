<?php

namespace App\Http\Controllers\Admin;

use App\Contract\Repository\Comment as RepositoryComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BatchRequest;
use App\Model\Comment;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RepositoryComment $repository)
    {
        $comments = $repository->getUnVerified();

        return response()->success($comments);
    }

    /**
     * Comment be verified .
     *
     * @return \Illuminate\Http\Response
    */
    public function approve(BatchRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            foreach($data['ids'] as $id)  {
                Comment::select('id', 'verified', 'able_type', 'able_id', 'level', 'root_id')
                    ->find($id)
                    ->update(['verified' => 'yes']);
            }
        });

        $count = count($data['ids']);
        return response()->success('', "操作成功,{$count}条记录被通过审批");
    }

    /**
     * Batch reject comments
     * 
     * @param BatchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function reject(BatchRequest $request, RepositoryComment $repository)
    {
        $ids = $request->validated()['ids'];

        DB::transaction(function () use ($ids, $repository) {
            foreach($ids as $id) {
                $repository->find($id)->delete();
            }
        });

        $count = count($ids);
        return response()->success('', "操作成功,{$count}条记录被移除");
    }
}
