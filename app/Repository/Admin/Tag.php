<?php

namespace App\Repository\Admin;

use App\Facades\Page;
use Illuminate\Http\Request;
use App\Model\Tag as ModelTag;
use Illuminate\Validation\ValidationException;
use App\Contract\Repository\Tag as RepositoryTag;
use Illuminate\Database\Eloquent\Collection;

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
     * @return array
     */
    public function all(?Request $request): array
    {
        $this->validateRequest($request);

        // 根据父标签获取 子标签
        $result = $this->getTags($request);

        // 没有记录，直接返回 || 请求的是二级标签，直接返回
        if (!$result['pagination']['total'] || $request->input('parent_id')) {
            if ($name = $request->input('name')) {
                return $this->searchByName($name);
            }
            return $result;
        }
        
        $result['records'] = $this->attemptGetSubTags($result['records']);

        return $result;
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
            'parent_id' => 'sometimes|required|integer|min:0',
            'name' => 'sometimes|required'
        ]);
    }

    /**
     * 通过父id获取标签
     *
     * @param integer|Request $request
     * @return array
     */
    protected function getTags($request)
    {
        $query = $this->model
            ->select('id', 'name', 'cover', 'parent_id', 'description', 'created_at');

        if ($request instanceof Request) {

            $query->where('parent_id', $request->input('parent_id', 0));

            if ($name = $request->input('name')) {
                $query->where('name', 'like', "%{$name}%");
            }
        } else {
            $query->where('parent_id', $request);
        }

        return Page::paginate($query);
    }

    /**
     * Attempt get sub tags for each parent tag
     *
     * @param Collection $records
     * @return void
     */
    protected function attemptGetSubTags(Collection $records)
    {
        // 获取二级标签
        $tags = $records->toArray();

        $counter = 1;

        foreach ($tags as $key => $tag) {

            // 标签只有两个层级,顶级标签的parent_id = 0
            if ($tag['parent_id']) {
                continue;
            }

            $subTags = $this->getTags($tag['id']);

            $childCount = $subTags['records']->count();
            // 如果没有子标签，跳过
            if (!$childCount) {
                continue;
            }

            $arr = $subTags['records']->toArray();

            // 判断是否还有更多的子标签
            if ($subTags['pagination']['currentPage'] < $subTags['pagination']['pages']) {
                array_push($arr, ['parent_id' => $tag['id'], 'hasMore' => true, 'p' => 1]);
            }

            array_splice($tags, $key + $counter, 0, $arr);
            $counter += $childCount;
        }
        
        return $tags;
    }

    /**
     * 通过name获取二级标签
     *
     * @param string $name
     * @return array
     */
    public function searchByName($name)
    {
        $query = $this->model->select('id', 'name', 'cover', 'parent_id', 'description', 'created_at')
            ->where('parent_id', '>', 0)
            ->where('name', 'like', "%$name%");

        return Page::paginate($query);
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
