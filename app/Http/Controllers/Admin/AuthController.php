<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Model\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthRequest;
use App\Contract\Repository\Auth as RepositoryAuth;

/**
 * @group Role base access control management
 * 
 * RBAC-权限管理
 * Auth Management
 */
class AuthController extends Controller
{
    /**
     * Display auth records
     * 
     * 显示所有的权限
     *
     * @responseFile response/admin/auth/index.json
     * 
     * @param Request $request
     * @param RepositoryAuth $repository
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, RepositoryAuth $repository)
    {
        $result = $repository->all($request);

        return response()->success($result);
    }

    /**
     * Get auth records when create new auth
     * 
     * 获取创建权限所需的数据
     * Show the form data for creating a new resource.
     * 
     * @responseFile response/admin/auth/create.json
     *
     * @param RepositoryAuth $repository
     * @return \Illuminate\Http\Response
     */
    public function create(RepositoryAuth $repository)
    {
        $result = $repository->flatted();

        return response()->success($result);
    }

    /**
     * Store newly created auth
     * 
     * 新建一个权限记录
     *
     * @bodyParam name string   required   权限名称
     * @bodyParam parent_id int required   父权限id,该值为0的时候,表示该权限是顶级权限
     * @bodyParam route_name string        权限对应的路由名称
     * @responseFile response/admin/auth/store.json
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthRequest $request)
    {
        $data = $request->validated();

        $auth = '';
        
        DB::transaction(function () use ($data, &$auth) {
            $auth = Auth::create($data);
        });

        return response()->success($auth, '权限添加成功');
    }

    /**
     * Update the specific auth
     * 
     * 更新指定的权限数据
     *
     * @urlParam auth 权限id
     * @bodyParam name string   required   权限名称
     * @bodyParam parent_id int required   父权限id,该值为0的时候,表示该权限是顶级权限
     * @bodyParam route_name string        权限对应的路由名称
     * @responseFile response/admin/auth/update.json
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Auth  $auth
     * @return \Illuminate\Http\Response
     */
    public function update(AuthRequest $request, Auth $auth)
    {
        $data = $request->validated();

        DB::transaction(function () use (&$auth, $data) {
            $auth->update($data);
        });
        
        return response()->success($auth, '权限修改成功');
    }

    /**
     * Destroy the specific auth
     * 
     * 删除权限,同时角色对应的权限也会移除
     *
     * @urlParam auth 权限id
     * @responseFile response/admin/auth/destroy.json
     * @param  \App\Model\Auth  $auth
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auth $auth)
    {
        DB::transaction(function () use ($auth) {
            $auth->delete();
        });

        return response()->success('', '权限删除成功');
    }
}
