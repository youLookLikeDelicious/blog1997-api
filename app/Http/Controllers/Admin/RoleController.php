<?php

namespace App\Http\Controllers\Admin;

use App\Model\Role;
use Illuminate\Support\Facades\DB;
use App\Contract\Repository\Role as Repository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Http\Request;

/**
 * @group Role Base Access Control Management
 * 
 * 基于角色的权限控制
 */
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * 获取所有的角色列表
     * 
     * @responseFile response/admin/role/index.json
     * @param Request $request
     * @param Repository $repository
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Repository $repository)
    {
        $result = $repository->all($request);
        return response()->success($result);
    }


    /**
     * Store a newly created role in storage.
     * 
     * 新建角色
     *
     * @bodyParam name          string required 角色名称
     * @bodyParam remark        string          备注
     * @bodyParam authorities   array           权限列表
     * @bodyParam authorities.* int  required 权限id
     * @responseFile response/admin/role/store.json
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $data = $request->validated();
        $authorities = $data['authorities'];
        unset($data['authorities']);

        $role = '';
        DB::transaction(function () use ($data, $authorities, &$role) {
            $role = Role::create($data);
            $role->authorities()->attach($authorities);
        });

        // 载入多对多关系
        $role->load('authorities:id,name');
        return response()->success($role, '角色添加成功');
    }

    /**
     * Update a specified role in storage.
     * 
     * 更新角色
     *
     * @urlParam role           角色id
     * @bodyParam name          string required 角色名称
     * @bodyParam remark        string          备注
     * @bodyParam authorities   array           权限列表
     * @bodyParam authorities.* int  required 权限id
     * @responseFile response/admin/role/update.json
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        $data = $request->validated();
        $authorities = $data['authorities'];
        unset($data['authorities']);

        DB::transaction(function () use ($data, $authorities, $role) {
            $role->update($data);
            $role->authorities()->sync($authorities);
        });

        $role->load('authorities:id,name');
        return response()->success($role, '角色更新成功');
    }

    /**
     * Remove the specified role from storage.
     *
     * 移除角色,同时也会移除和权限对应的多对多关系
     * 
     * @urlParam role 角色ID
     * @responseFile response/admin/role/destroy.json
     * @param  \App\Model\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        DB::transaction(function () use ($role) {
            $role->delete();
            $role->authorities()->detach();
        });

        return response()->success('', '角色删除成功');
    }
}
