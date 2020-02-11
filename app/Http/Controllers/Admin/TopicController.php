<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Topic;
use App\Model\Article;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    /**
     * 获取专题列表
     * Method GET
     * @param Request $request
     * @param Topic $topic
     * @return mixed
     */
    public function getTopicList (Request $request, Topic $topic) {
        $topicQuery = $topic->select(['id', 'name', 'article_sum', 'created_at']);

        try{
            $topicList = \Page::paginate($topicQuery);
            return response()->success($topicList);
        } catch (\Exception $e) {
            return response()->error('异常错误,请联系站长');
        }
    }

    /**
     * 验证提交的数据
     * @param $data 需要验证的数据
     * @return validator
     */
    protected function validator($data){
        $message = [
            'id.numeric' => '专题id应该是一个数字',
            'name.required' => '专题名不能为空',
            'name.max' => '专题名的长度不能超过:max',
            'name.unique'   => '专题名不能重复'
        ];
        $validator = Validator::make($data, [
           'id' => 'nullable|numeric',
            'name' => 'required|max:15|unique:topic'
        ], $message);

        return $validator;
    }

    /**
     * 新建专题
     * Method POST
     * @param Request $request
     * @param Topic $topic
     * @return mixed
     */
    public function createTopic (Request $request, Topic $topic) {
        // 获取专题名称和id
        $data = [];
        $data['name'] = $request->input('topicName', null);
        $data['id'] = $request->input('topicId');

        // 验证提交的数据
        $validator = $this->validator($data);

        if ($validator->fails()) {
            return response()->error($validator->errors());
        }

        // 修改操作
        if ($data['id']) {
            $flag = $topic->where('id', $data['id'])->update($data);
            $message = '专题修改成功';
        } else {
            // 新建操作
            unset($data['id']);
            $flag = $topic->create($data);
            $message = '专题创建成功';
        }

        if ($flag) {
            // 专题创建成功，并返回新的专题里列表
            $topicList = $topic->select(['id', 'name', 'article_sum', 'created_at'])->get();

            return response()->success($topicList, $message);
        } else{
            return response()->error('未知错误,请联系站长');
        }
    }

    /**
     * 删除专题
     * Method POST
     * @param Request $request
     * @return mixed
     */
    public function deleteTopic (Request $request) {
        $id = $request->input('id', 0) + 0;

        DB::beginTransaction();

        try{
            // 删除专题
            $flag = Topic::where('id', $id)->delete();

            // 删除专题下的文章
            $article = Article::where('topic_id', $id)->get();

            foreach ($article as $v) {
                $v->delete();
            }

            // 删除成功
            DB::commit();
            return response()->success();
        } catch (\Exception $e) {
            // 删除失败
            DB::rollBack();
            return response()->error('专题删除失败');
        }
    }
}
