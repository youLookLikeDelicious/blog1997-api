<?php
namespace App\Repository\Admin;

use App\Facades\Page;
use Illuminate\Http\Request;
use App\Model\FriendLink as ModelFriendLink;
use App\Contract\Repository\FriendLink as RepositoryFriendLink;

class FriendLink implements RepositoryFriendLink
{
    /**
     * 友链模型
     *
     * @var App\Model\FriendLink
     */
    protected $model;

    public function __construct(ModelFriendLink $model)
    {
        $this->model = $model;
    }

    public function all(Request $request = null)
    {
        $this->validateRequest($request);

        $friendLInkQuery = $this->buildQuery($request);

        $friendLInk = Page::paginate($friendLInkQuery);
        
        return $friendLInk;
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
        $query = $this->model->selectRaw('id, name, url, false as editAble');
        
        if ($name = $request->input('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        return $query;
    }
}