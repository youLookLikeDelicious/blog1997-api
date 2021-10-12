<?php

namespace App\Repository\Admin;

use Illuminate\Http\Request;
use App\Model\Tag as ModelTag;
use App\Http\Resources\CommonCollection;
use Illuminate\Validation\ValidationException;
use App\Contract\Repository\Tag as RepositoryTag;

class Tag implements RepositoryTag
{
    /**
     * Tag Eloquent
     *
     * @var ModelTag
     */
    protected $model;

    public function __construct(ModelTag $model)
    {
        $this->model = $model;
    }

    /**
     * 获取标签
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all(?Request $request)
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
    protected function validateRequest(?Request $request)
    {
        if (! ($request instanceof Request)) {
            return;
        }
        
        $request->validate([
            'name' => 'sometimes|required'
        ]);
    }

    /**
     * Build database query by request
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQuery($request)
    {
        $query = $this->model->select('id', 'name', 'cover', 'parent_id', 'description', 'user_id', 'created_at')
            ->where('user_id', 0)
            ->where('parent_id', 0)
            ->with('child');

        if ($name = $request->input('name')) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        return $query;
    }

    /**
     * 通过id获取指定的标签
     *
     * @param integer $id
     * @return \App\Model\Tag
     */
    public function find(int $id)
    {
        return $this->model
            ->select('id', 'name', 'cover', 'parent_id', 'description', 'created_at')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    }

    /**
     * 获取所有标签
     * 将结果作为一维数组展示
     *
     * @param boolean $onlyTopTags 是否只获取顶级标签
     * @return array
     */
    public function flatted($onlyTopTags = false)
    {
        $query = $this->model
            ->select('id', 'name');

        if ($onlyTopTags) {
            $query->where('parent_id', 0);
        } else {
            $userId = auth()->id();
            $query->where('parent_id', '>=', 0)
                ->OrWhereRaw("user_id = {$userId} and parent_id = -1");
        }

        $result = $query->get();

        return $result;
    }

    /**
     * Check tag is exists with specify contract
     *
     * @param array $contract
     * @return boolean
     */
    public function exists(array $contract, ?ModelTag $tag = null)
    {
        $query = $this->model->select('id', 'name', 'user_id', 'parent_id');

        foreach($contract as $key => $v) {
            $query->where($key, $v);
        }

        if ($tag) {
            $query->where('id', '!=', $tag->id);
        }

        $tag = $query->first();

        return $tag;
    }
}
