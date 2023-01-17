<?php
namespace App\Repository\Admin;

use App\Facades\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\SensitiveWordCategory as ModelSensitiveWordCategory;
use App\Contract\Repository\SensitiveWordCategory as RepositorySensitiveWordCategory;
use App\Http\Resources\CommonCollection;

class SensitiveWordCategory implements RepositorySensitiveWordCategory
{
    /**
     * Eloquent ORM
     *
     * @var App\Models\SensitiveWordCategory
     */
    protected $model;

    public function __construct(ModelSensitiveWordCategory $model)
    {
        $this->model = $model;
    }

    /**
     * Get all sensitive category
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all(Request $request)
    {
        $this->validateRequest($request);

        $data = $this->buildQuery($request)->paginate($request->input('perPage', 10));

        return new CommonCollection($data);
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
            'name' => 'sometimes|required',
            'rank' => 'sometimes|required|integer|min:1'
        ]);
    }

    /**
     * Build query with incoming request
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery(Request $request)
    {
        $query = $this->model->selectRaw('id, name, count, rank');

        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        if ($rank = $request->input('rank')) {
            $query->where('rank', $rank + 0);
        }

        return $query;
    }

    public function list()
    {
        $categoryList = $this->model->select(['id', 'name'])->get();

        return $categoryList;
    }
}