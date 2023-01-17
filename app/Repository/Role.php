<?php
namespace App\Repository;

use App\Facades\Page;
use Illuminate\Http\Request;
use App\Models\Role as Model;
use Illuminate\Validation\ValidationException;
use App\Contract\Repository\Role as RepositoryRole;
use App\Http\Resources\RoleCollection;

class Role implements RepositoryRole
{
    /**
     * Role Eloquent
     *
     * @var \App\Models\Role
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * 分页获取所有的权限
     *
     * @param boolean $paginate 是否分页
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all(Request $request, $paginate = true)
    {
        $query = $this->buildQuery($request);
        
        $data = $paginate
            ? $query->paginate($request->input('page', 10))
            : $query->get();

        return new RoleCollection($data);
    }

    /**
     * Build query with incoming request
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery($request)
    {
        $query = $this->model->select(['id', 'name', 'remark'])
            ->with('authorities:id,name');

        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        return $query;
    }

    /**
     * Validate incoming request
     *
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required'
        ]);
    }

    /**
     * 获取所有的role
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function flatted()
    {
        $roles = $this->model->select(['id', 'name'])->get();

        return $roles;
    }
}