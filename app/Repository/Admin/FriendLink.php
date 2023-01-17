<?php
namespace App\Repository\Admin;

use Illuminate\Http\Request;
use App\Models\FriendLink as ModelFriendLink;
use App\Contract\Repository\FriendLink as RepositoryFriendLink;
use App\Http\Resources\CommonCollection;

class FriendLink implements RepositoryFriendLink
{
    /**
     * 友链模型
     *
     * @var \App\Models\FriendLink
     */
    protected $model;

    public function __construct(ModelFriendLink $model)
    {
        $this->model = $model;
    }

    /**
     * Get friend link list
     *
     * @param Request|null $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all(Request $request = null)
    {
        $this->validateRequest($request);

        $friendLInkQuery = $this->buildQuery($request);

        $friendLInk = $friendLInkQuery->paginate($request->input('perPage'));
        
        return new CommonCollection($friendLInk);
    }

    protected function validateRequest(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required'
        ]);
    }

    /**
     * Build select query with incoming request
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery(Request $request)
    {
        $query = $this->model->selectRaw('id, name, url, created_at');
        
        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        return $query;
    }
}