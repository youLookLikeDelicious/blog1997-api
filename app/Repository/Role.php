<?php
namespace App\Repository;

use App\Facades\Page;
use Illuminate\Http\Request;
use App\Model\Role as Model;
use Illuminate\Validation\ValidationException;
use App\Contract\Repository\Role as RepositoryRole;

class Role implements RepositoryRole
{
    /**
     * Role Eloquent
     *
     * @var \App\Model\Role
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * 分页获取所有的权限
     *
     * @return array
     */
    public function all(Request $request)
    {
        $query = $this->buildQuery($request);

        $result = Page::paginate($query);

        return $result;
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