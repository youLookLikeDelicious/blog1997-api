<?php

namespace App\Http\Controllers\Admin;

use App\Model\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TopicRequest;
use App\Contract\Repository\Topic as Repository;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    /**
     * 获取专题列表
     * Method GET
     * 
     * @param \App\Repository\Admin\Topic $repository 
     * @return Response
     */
    public function index (Request $request, Repository $repository)
    {
        $result = $repository->paginate($request);

        return response()->success($result);
    }

    /**
     * 新建专题
     * Method POST
     * @param Request $request
     * @return mixed
     */
    public function store (TopicRequest $request) {
        // 验证提交的数据
        $data = $request->validated();

        $topicModel = Topic::create($data);

        return response()->success($topicModel->append('editAble'), '专题创建成功');
    }

    /**
     * 更新操作
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(TopicRequest $request, $id)
    {
        // 验证提交的数据
        $data = $request->validated();

        $topicModel = Topic::findOrFail($id);

        $topicModel->update($data);

        return response()->success($topicModel->append('editAble'), '专题修改成功');
    }

    /**
     * 删除专题
     * Method DELETE
     * 
     * @param Request $request
     * @return mixed
     */
    public function destroy ($id)
    {
        DB::transaction(function () use ($id) {
            Topic::findOrFail($id)->delete();
        });

        return response()->success('', '专题删除成功');
    }
}
