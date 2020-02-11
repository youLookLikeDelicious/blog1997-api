<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Model\FriendLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendLinkController extends Controller
{
    /**
     * 获取友链列表
     * Method GET
     * @param Request $request
     * @return mixed
     */
    public function getList (Request $request) {
        $friendLInkQuery = FriendLink::select(['id', 'name', 'url']);

        $friendLInk = $friendLInkQuery->get();
        $friendLInk = \Page::paginate($friendLInkQuery);
        return response()->success($friendLInk);
    }

    /**
     * 新建友链数据
     * Method POST
     * @param Request $request
     * @return mixed
     */
    public function createFriendLink (Request $request) {
        // 获取表单数据
        $data = $request->only(['id', 'name', 'url']);

        // 验证表单数据
        $validator = $this->validator($data);
        if ($validator->fails()) {
            return response()->error($validator->errors());
        }

        $id = $data['id'];
        unset($data['id']);

        if ($id) {
            // 更新操作
            $flag = FriendLink::where('id', $id)->update($data);
            $message = $flag ? '友链修改成功' : '友链修改失败';
        } else {
            // 新建操作
            $flag = FriendLink::create($data);
            $message = $flag ? '友链添加成功' : '友链添加失败';
        }

        if ($flag) {
            return response()->success('', $message);
        }

        return response()->error($message);
    }

    /**
     * 删除友链
     * Method POST
     */
    public function deleteFriendLink (Request $request) {
        // 获取友链的id
        $id = $request->input('id', 0) + 0;

        $flag = FriendLink::where('id', $id)->delete();

        if ($flag) {
            return response()->success('', '友链删除成功');
        } else {
            return response()->error('友链删除失败');
        }
    }
    /**
     * 验证表单数据
     * @param $data
     * @return mixed
     */
    protected function validator ($data) {
        $rul = [
            'id' => 'nullable|numeric',
            'name' => 'required|max:21',
            'url'  => 'required|max:120'
        ];

        $message = [
            'id.numeric' => 'id应该是一个数字',
            'name.required' => '网站名称必填',
            'name.max' => '网站名称最多为:max个字符',
            'url.required' => '网站地址必填',
            'url.max' => '网站地址最多:max个字符',
        ];

        $validator = Validator::make($data, $rul, $message);
        return $validator;
    }
}
