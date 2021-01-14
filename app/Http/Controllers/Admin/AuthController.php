<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Model\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthRequest;
use App\Contract\Repository\Auth as RepositoryAuth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, RepositoryAuth $repository)
    {
        $result = $repository->all($request);

        return response()->success($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(RepositoryAuth $repository)
    {
        $result = $repository->flatted();

        return response()->success($result);
    }

    /**
     * Store a newly created resource in storage.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     *
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
