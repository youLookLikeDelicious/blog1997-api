<?php
namespace App\Repository\Admin;

use App\Facades\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Model\SensitiveWordCategory as ModelSensitiveWordCategory;
use App\Contract\Repository\SensitiveWordCategory as RepositorySensitiveWordCategory;

class SensitiveWordCategory implements RepositorySensitiveWordCategory
{
    /**
     * Eloquent ORM
     *
     * @var App\Model\SensitiveWordCategory
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
     * @return array
     */
    public function all(Request $request)
    {
        $this->validateRequest($request);

        $query = $this->buildQuery($request);

        return Page::paginate($query);
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
        $query = $this->model->selectRaw('id, name, count, rank, false as editAble');

        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        if ($rank = $request->input('rank')) {
            $query->where('rank', $rank + 0);
        }

        return $query;
    }
}