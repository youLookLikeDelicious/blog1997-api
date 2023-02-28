<?php

namespace App\Http\Controllers\Admin;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TopicRequest;
use App\Contract\Repository\Topic as Repository;
use Illuminate\Support\Facades\DB;

/**
 * @group Topic management
 * 
 * 文章专题管理
 */
class TopicController extends Controller
{
    /**
     * Display tops records
     * 
     * 显示专题列表
     * 
     * @queryParam p 请求的页数
     * @responseFile response/admin/topic/index.php
     * @param Request $request
     * @param Repository $repository
     * @return \Illuminate\Http\Response
     */
    public function index (Request $request, Repository $repository)
    {
        $result = $repository->paginate($request);

        return $result->toResponse($request);
    }

    /**
     * Store newly created topic
     * 
     * 新建专题
     * 
     * @bodyParam name string required 专题名称(唯一属性)
     * @responseFile response/admin/topic/store.php 
     * @param TopicRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store (TopicRequest $request) {
        // 验证提交的数据
        $data = $request->validated();

        $topicModel = Topic::create($data);

        return response()->success('', '专题创建成功');
    }

    /**
     * Update the specific topic
     * 
     * 更新专题
     *
     * @urlParam topic 专题ID
     * @bodyParam name string required 专题名称(唯一属性)
     * @responseFile response/admin/topic/store.php 
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Illuminate\Http\Response
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        // 验证提交的数据
        $data = $request->validated();

        $topic->update($data);

        return response()->success('', '专题修改成功');
    }

    /**
     * Destroy the specific topic
     * 
     * 删除指定的专题
     * 该专题下的所有文章会被删除
     * 
     * @urlParam topic 专题ID
     * @responseFile response/admin/topic/destroy.php 
     * @param Topic $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy (Topic $topic)
    {
        DB::transaction(function () use ($topic) {
            $topic->delete();
        });

        return response()->success('', '专题删除成功');
    }

    /**
     * Get all topics
     * 
     * 获取全部专题
     *
     * @return void
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $topics = Topic::where('user_id', 1)->get();

        return response()->success($topics);
    }
}
