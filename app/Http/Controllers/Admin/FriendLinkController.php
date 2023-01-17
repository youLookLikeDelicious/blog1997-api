<?php

namespace App\Http\Controllers\Admin;

use App\Models\FriendLink;
use App\Http\Controllers\Controller;
use App\Http\Requests\FriendLinkRequest;
use App\Contract\Repository\FriendLink as RepositoryFriendLink;
use Illuminate\Http\Request;

/**
 * @group Friend link management
 * 
 * 友情连接管理
 * Friend Link Management
 */
class FriendLinkController extends Controller
{
    /**
     * Get friend link records
     * 
     * 获取友链列表
     * 
     * @responseFile response/admin/friend-link/index.json
     * @param Request $request
     * @param RepositoryFriendLink $friendLInk
     * @return \Illuminate\Http\Response
     */
    public function index (Request $request, RepositoryFriendLink $friendLInk)
    {        
        $result = $friendLInk->all($request);

        return $result->toResponse($request);
    }

    /**
     * Store newly created friend link records
     * 
     * 新建友链数据
     * 
     * @bodyParam name string required 友链名称,例如Blog1997
     * @bodyParam url  string required 友链地址,例如https://www.blog1997.com
     * @responseFile response/admin/friend-link/store.json
     * @param FriendLinkRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store (FriendLinkRequest $request)
    {
        $data = $request->validated();

        FriendLink::create($data);
        
        return response()->success('', '友链添加成功');
    }

    /**
     * Update newly specified friend link record
     * 
     * 更新友链
     *
     * @urlParam friendLink 友链记录ID
     * @bodyParam name string required 友链名称,例如Blog1997
     * @bodyParam url  string required 友链地址,例如https://www.blog1997.com
     * @responseFile response/admin/friend-link/update.json
     * @param FriendLinkRequest $request
     * @param FriendLink $friendLink
     * @return \Illuminate\Http\Response
     */
    public function update(FriendLinkRequest $request, FriendLink $friendLink)
    {
        $data = $request->validated();

        $friendLink->update($data);
        
        return response()->success('', '友链修改成功');
    }

    /**
     * Destroy newly specified friend link record
     * 
     * 删除友链
     * 
     * @urlParam friendLink 友链ID
     * @responseFile response/admin/friend-link/destroy.json
     * @param FriendLink $friendLInk
     * @return \Illuminate\Http\Response
     */
    public function destroy (FriendLink $friendLink)
    {
        $friendLink->delete();

        return response()->success('', '友链删除成功');
    }
}
