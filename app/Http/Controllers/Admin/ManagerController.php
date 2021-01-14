<?php

namespace App\Http\Controllers\Admin;

use App\Model\User;
use App\Contract\Repository\Role;
use App\Contract\Repository\User as RepositoryUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManagerRequest;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, RepositoryUser $repository)
    {
        $result = $repository->getManagers($request);

        return response()->success($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Role $repository)
    {
        $roles = $repository->flatted();

        return response()->success($roles);
    }

    /**
     * Update user roles in storage.
     *
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

        $manager->load('roles:role.id,name');

        $manager->makeHidden(['remember_token', 'password', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at']);
        
        return response()->success($manager, '管理员修改成功');
    }

    /**
     * Get user and roles information after enter user email
     *
     * @param RepositoryUser $repository
     * @param string $email
     * @return \Illuminate\Http\Response
     */
    public function user(RepositoryUser $repository, $email = '')
    {
        $user = $repository->findByEmail($email);
        return response()->success($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $manager)
    {
        DB::transaction(function () use ($manager) {
            $manager->softDelete();
        });

        return response()->success('', '管理员删除成功');
    }
}
