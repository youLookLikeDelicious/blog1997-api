<?php
namespace App\Repository\Admin;

use Illuminate\Http\Request;
use App\Models\Auth as ModelAuth;
use App\Contract\Repository\Auth as RepositoryAuth;

class Auth implements RepositoryAuth
{
    /**
     * Auth Eloquent
     *
     * @var ModelAuth
     */
    protected $model;

    public function __construct(ModelAuth $model)
    {
        $this->model = $model;
    }

    /**
     * 分页获取所有的权限
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(Request $request)
    {
        return $this->model->select(['id', 'name', 'route_name', 'parent_id'])
            ->where('parent_id', 0)->with('child')
            ->get();
    }

    /**
     * 将结果作为一维数组展示
     *
     * @return array
     */
    public function flatted()
    {
        $result = $this->model
            ->select(['id', 'name', 'auth_path', 'parent_id'])
            ->orderBy('auth_path', 'ASC')
            ->get()
            ->toArray();

        return $result;
    }

    /**
     * 获取权限的树形结构
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAuthTree()
    {
        $data = $this->model->with('child')->get();

        return $data;
    }

    /**
     * Get all authority name
     *
     * @return \Illuminate\Support\Collection
     */
    public function routeNames()
    {
        $authorities = $this->model
            ->select('route_name')
            ->where('route_name', '!=', '')
            ->get()
            ->toArray();

        return collect($authorities)->pluck('route_name');
    }

    /**
     * Validate request
     *
     * @param Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required',
            'parent_id' => 'sometimes|required|integer|min:0',
        ]);
    }
}