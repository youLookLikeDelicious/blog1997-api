<?php

namespace App\Http\Controllers\Admin;

use App\Model\Role;
use Illuminate\Support\Facades\DB;
use App\Contract\Repository\Role as Repository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Repository $repository)
    {
        $result = $repository->all($request);
        return response()->success($result);
    }


    /**
     * Store a newly created resource in storage.
     *
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
     * Update the specified resource in storage.
     *
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
     * Remove the specified resource from storage.
     *
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
