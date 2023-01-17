<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Contract\Repository\Role;
use App\Contract\Repository\User as RepositoryUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManagerRequest;
use Illuminate\Http\Request;

/**
 * @group Role base access control management
 * 
 * RBAC-管理员管理
 * Manager Management
 */
class ManagerController extends Controller
{
    /**
     * Get user info when assign roles
     * 
     * 为角色赋予管理员时,获取所有的角色列表
     * Show the form for creating a new resource.
     *
     * @responseFile response/admin/manager/create.json
     * @param Role $repository
     * @return \Illuminate\Http\Response
     */
    public function create(Role $repository)
    {
        $roles = $repository->flatted();

        return response()->success($roles);
    }

    /**
     * Resign roles to user
     * 
     * 更新管理员角色
     * Update user roles in storage.
     *
     * @urlParam  manager 用户ID
     * @bodyParam email   string required 用户邮箱
     * @bodyParam roles   array  required 角色ID列表
     * @bodyParam roles.* int    required 角色ID
     * @responseFile response/admin/manager/update.json
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $manager
     * @return \Illuminate\Http\Response
     */
    public function update(ManagerRequest $request, User $manager)
    {
        $data = $request->validated();

        DB::transaction(function () use($data, &$manager) {
            $manager->roles()->sync($data['roles']);
        });

        $manager->makeHidden(['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at']);
        
        return response()->success($manager, '管理员修改成功');
    }

    /**
     * Get user records when assign roles to user 
     * 
     * 分配权限的时候,获取用户信息
     * Get user and roles information after enter user email
     *
     * @urlParam email 用户邮箱
     * @responseFile response/admin/manager/user.json
     * @param RepositoryUser $repository
     * @param string $email
     * @return \Illuminate\Http\Response
     */
    public function user(RepositoryUser $repository, $email = '')
    {
        $user = $repository->findByEmail($email);
        
        return response()->success($user);
    }
}
