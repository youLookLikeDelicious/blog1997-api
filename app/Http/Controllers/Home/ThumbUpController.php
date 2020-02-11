<?php

namespace App\Http\Controllers\Home;

use Validator;
use App\Model\ThumbUp;
use Illuminate\Http\Request;
use App\Foundation\RedisCache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class ThumbUpController extends Controller
{
    /**
     * 点赞操作
     * Method POST
     * @param Request $request
     * @param $action
     * @return mixed
     */
    public function thumbUp (Request $request, $action) {

        // 获取表单数据
        $data = $request->only(['id', 'category', 'user_id']);

        // 验证表单数据
        $validator = $this->validator($data);

        // 验证失败
        if ($validator->fails()) {
            return response()->error($validator->errors());
        }

        // 获取当前用户id
        $userId = $data['user_id'];
        $result = Redis::setnx("thumb-up-{$data['category']}-{$data['id']}-{$userId}", 1);

        if (!$result) {
            return response()->error('操作过于频繁');
        }

        DB::beginTransaction();

        try {
            // 入库操作
            if ($action === 'decrement') {

                $message = '取消点赞';

                $this->decrementThumb($data);
            } else {

                $message = '点赞';

                $this->incrementThumb($data);
            }

            DB::commit();

            return response()->success(null, $message . '成功');
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->error($e->getMessage());
        } finally {
            Redis::del("thumb-up-{$data['category']}-{$data['id']}-{$userId}");
        }
    }

    /**
     * 验证表单数据
     * @param $data
     * @return mixed
     */
    protected function validator ($data) {
        $rules = [
            'id' => 'required|numeric',
            'category' => 'required|in:article,comment'
        ];

        $message = [
            'id' => '未知的id',
            'category' => '未知的类型',
        ];

        $validator = Validator::make($data, $rules, $message);

        return $validator;
    }

    /**
     * 增加点赞数
     * @param $data
     */
    protected function incrementThumb ($data) {

        $thumbUpData = [
            'user_id' => $data['user_id'],
            'thumbable_id' => $data['id'],
            'thumbable_type' => $data['category'] === 'article' ? 'App\Model\Article' : 'App\Model\Comment'
        ];

        \RedisCache::{"incr{$data['category']}Liked"}($data['id']);

        ThumbUp::create($thumbUpData);
    }

    /**
     * 减少点赞数
     * @param $data
     */
    protected function decrementThumb ($data) {
        \RedisCache::{"decr{$data['category']}Liked"}($data['id']);

        ThumbUp::where('user_id', $data['user_id'])
            ->where('thumbable_id', $data['id'])
            ->delete();
    }
}
