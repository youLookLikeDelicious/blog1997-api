<?php
namespace App\Repository\Admin;

use App\Facades\Page;
use Illuminate\Http\Request;
use App\Model\Auth as ModelAuth;
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
     * 获取所有的权限
     *
     * @param Request $request
     * @return array
     */
    public function all(Request $request)
    {
        $this->validateRequest($request);

        $query = $this->buildQuery($request);

        $result = Page::paginate($query);

        // 获取哦所有顶级权限
        $topAuth = $this->model->select(['id', 'name'])
            ->where('parent_id', 0)
            ->get()->toArray();

        array_unshift($topAuth, ['id' => '', 'name' => '--所有权限--']);

        $result['topAuth'] = $topAuth;
        
        return $result;
    }

    /**
     * Build request query
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery(Request $request)
    {
        $query = $this->model
            ->select(['id', 'name', 'parent_id', 'auth_path', 'route_name'])
            ->orderBy('auth_path', 'ASC');

        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->input('parent_id')) {
            $query->where('auth_path', 'like', $request->input('parent_id') . '_%');
        }

        return $query;
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

        array_unshift($result, ['id' => 0, 'name' => '--顶级权限--']);

        return $result;
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