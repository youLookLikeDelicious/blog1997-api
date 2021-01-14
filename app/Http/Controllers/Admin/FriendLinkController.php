<?php

namespace App\Http\Controllers\Admin;

use App\Model\FriendLink;
use App\Http\Controllers\Controller;
use App\Http\Requests\FriendLinkRequest;
use App\Contract\Repository\FriendLink as RepositoryFriendLink;
use Illuminate\Http\Request;

class FriendLinkController extends Controller
{
    /**
     * 获取友链列表
     * Method GET
     * @param Request $request
     * @return mixed
     */
    public function index (Request $request, RepositoryFriendLink $friendLInk)
    {        
        $result = $friendLInk->all($request);

        return response()->success($result);
    }

    /**
     * 新建友链数据
     * Method POST
     * 
     * @param Request $request
     * @return mixed
     */
    public function store (FriendLinkRequest $request) {
        $data = $request->validated();

        $friendLInkModel = FriendLink::create($data);

        $friendLInkModel->append('editAble');
        
        return response()->success($friendLInkModel, '友链添加成功');
    }

    /**
     * 更新友链
     * Method PUT
     *
     * @param Type $var
     * @return void
     */
    public function update(FriendLinkRequest $request, FriendLink $friendLink)
    {
        $data = $request->validated();

        $friendLink->update($data);

        $friendLink->append('editAble');
        
        return response()->success($friendLink, '友链修改成功');
    }
    /**
     * 删除友链
     * Method Delete
     * 
     * @param FriendLink $friendLInk
     * @return Response
     */
    public function destroy (FriendLink $friendLink)
    {
        $friendLink->delete();

        return response()->success('', '友链删除成功');
    }
}
