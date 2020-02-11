<?php

namespace App\Http\Controllers\Home;

use Validator;
use App\Model\Comment;
use App\Facades\RedisCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class CommentController extends Controller
{
    protected $comment = NULL;

    /**
     * 上传评论
     * @param Request $request
     * @return mixed
     */
    public function create (Request $request) {

        $data = $request->except('type');
        $validator = $this->validator($data);

        // 验证字段
        if( $validator->fails() ){
            return response()->error($validator->errors());
        }

        // 过滤html标签
        $data['content'] = \removeXss($data['content']);

        if (!trim($data['content'])) {
            return response()->error('内容不能为空');
        }

        try{
            DB::beginTransaction();
            $newComment = Comment::create($data)->toArray();

            if (!$newComment) {
                throw new \Exception('异常错误');
            }

            // 提交事务
            DB::commit();

            $newComment['replies'] = [];

            return response()->success($newComment, '评论成功');
        } catch(\Exception $e){
            // 回滚事务
            DB::rollBack();

            return response()->error('评论失败');
        }
    }

    /**
     * 验证评论的内容
     * @param $data
     * @return mixed
     */
    public function validator($data){
        $rules = [
            'user_id'			  => 'required|numeric',
            'root_id'			  => 'nullable|numeric',
            'commentable_id' 	  => 'required|numeric',
            'commentable_type'    => 'required|max:45',
            'content' 			  => 'required|max:2100',
            'level'               => 'nullable|numeric|max:3',
            'reply_to'            => 'nullable|numeric',
            'reply_to_name'       => 'required|max:45'
        ];

        $message = [
            'content.required'			 => '内容不能为空',
            'content.max'				 => '内容过长',
            'user_id.required' 			 => '用户信息异常',
            'commentable_type.required'  => '被评论的模型为必填项'
        ];

        return Validator::make($data, $rules, $message);
    }

    /**
     * 分页获取评论以及评论的相关回复
     * Method GET
     * @param Request $request
     * @param $id
     * @param $type
     * @return mixed
     */
    public function get (Request $request, Comment $comment, $id) {

        $comments = $comment->getComment($id);

        return response()->success($comments);
    }

    /**
     * 获取回复
     * @param Request $request
     * @param $rootId
     * @param $offset
     * @return mixed
     */
    public function getReply (Request $request, Comment $comment, $rootId, $offset) {

        $comment = $comment->getReply($rootId, $offset);

        return response()->success($comment);
    }

    /**
     * 删除评论以及相关回复
     * Method POST
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function delete(Request $request) {
        $redisTransitionBegin = false;

        // 评论的id
        $id = $request->input('id', NULL);

        if ($id === NULL || !is_numeric($id)) {
            return response()->error('未知的comment id');
        }

        $commented = 0;

        // 获取被删除的记录信息
        // root_id 字段在observer中使用
        $comment = Comment::select(['level', 'commented', 'commentable_id', 'commentable_type', 'root_id', 'id'])->find($id);

        try {
            // 如果删除的是一级评论，同时调整文章的评论数
            if ($comment->level == '1') {
                // 获取评论的回复数量
                $commented = $comment->commented;

                $todayCommented = RedisCache::getCommentCommented($id);

                // 如果今天有过回复，
                if ($todayCommented) {
                    $commented += $todayCommented;
                }

                // redis中的相关记录-n
                RedisCache::decrCommentCommented($id, $commented);

                Redis::multi();

                $redisTransitionBegin = true;

                if ($comment->commentable_type === 'Blog1997') {
                    RedisCache::decrSiteCommented($commented + 1);
                } else {
                    RedisCache::decrArticleCommented($comment->commentable_id, $commented + 1);
                }

            }
            
            // 开启事务
            DB::beginTransaction();
            if (!$redisTransitionBegin) {
                Redis::multi();
            }
            
            $comment->delete();

            // 提交事务
            DB::commit();

            Redis::exec();

            // 生成返回的数据
            $data = [
                'rows' => $commented + 1 // 添加自身这条记录的数量
            ];

            return response()->success($data, '删除评论成功');
        } catch( \Exception $e ) {
            // 回滚事务
            DB::rollBack();

            Redis::discard();

            return response()->error($e->__toString());
        }
    }
}
