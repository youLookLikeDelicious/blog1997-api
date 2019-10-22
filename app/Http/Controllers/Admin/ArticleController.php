<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Model\Article;

class ArticleController extends Controller
{
    /**
     * 获取文章列表
     * Method GET
     */
    public function getArticleList (Request $request, $topicId) {
        // 获取专题id
        $topicId = $topicId + 0;
        $list = Article::select(['id', 'title', 'is_origin', 'visited', 'commented'])
            ->where('topic_id', $topicId);

        return response()->success(\Page::paginate($list));
    }

    /**
     * 获取文章的内容
     * @return mixed
     */
    public function getArticle (Request $request) {
        // 获取文章的id
        $id = $request->input('id', 0) + 0;

        $article = Article::select(['id', 'title', 'is_origin', 'topic_id', 'keywords', 'description', 'content', 'order_by'])
            ->find($id);

        if ($article) {
            return response()->success($article->toArray());
        } else {
            return response()->error('暂无该文章相关信息');
        }
    }

    /**
     * 新建 | 更新文章
     * Method POST
     */
    public function createArticle (Request $request) {
        // 获取表单数据
        $data = $request->only(['id', 'title', 'user_id', 'topic_id', 'is_origin', 'order_by', 'keywords', 'description', 'content']);

        // 验证表单数据
        $validator = $this->validator($data);
        if ($validator->fails()) {
            return response()->error($validator->errors());
        }

        // 删除id属性
        $id = $data['id'];
        unset($data['id']);

        // 获取文章的摘要信息
        $summary = preg_replace('/<[^>]*>/', '', $data['content']);
        $summary = mb_substr($summary, 0, 300);

        $data['summary'] = $summary;

        // 因为使用observer，索引使用事务
        DB::beginTransaction();

        if ($id) {
            // 修改操作,会触发observer
            $flag = Article::find($id)->update($data);
        } else {
            // 新建操作,会触发observer
            $flag = Article::create($data);
        }

        if ($flag) {
            // 操作成功
            DB::commit();
            return response()->success();
        } else {
            // 操作失败
            DB::rollBack();
            return response()->error();
        }
    }

    /**
     * 验证提交的表单数据
     * @param $data
     * @return mixed
     */
    protected function validator ($data) {
        $rule = [
            'id' => 'nullable|integer',
            'user_id' => 'required',
            'title' => 'required|max:25',
            'topic_id' => 'required',
            'is_origin' => 'required|in:yes,no',
            'order_by' => 'required|integer',
            'keywords' => 'required|max:210',
            'description' => 'required|max:330',
            'content' => 'required|max:30000'
        ];
        $message = [
            'user_id.required' => '非法的用户',
            'title.required' => '标题不能为空',
            'title.max' => '标题最大长度为:max',
            'topic_id.required' => '专题不能为空',
            'is_origin.required'  => '是否原创必填',
            'order_by.required'   => '排序不能为空',
            'order_by.integer'   => '排序字段必须为整数',
            'content.required'  => '内容不能为空',
            'content.max'   => '超过文章最大字数限制:max',
            'keywords.required'  => '关键字必填',
            'keywords.max'  => '关键超过最大字符限制 :max',
            'description.required' => '描述必填',
            'description.max' => '描述超过最大字符限制 ：max',
        ];

        $validator = Validator::make($data, $rule, $message);
        return $validator;
    }
}
