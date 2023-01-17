<?php
namespace App\Repository\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\ThumbUp as ModelThumbUp;
use App\Contract\Repository\ThumbUp as RepositoryThumbUp;

class ThumbUp implements RepositoryThumbUp
{
    /**
     * Thumb up Eloquent
     *
     * @var \App\Models\ThumbUp
     */
    protected $model;
    public function __construct(ModelThumbUp $model)
    {
        $this->model = $model;
    }
    /**
     * 获取总的点赞量
     *
     * @return integer
     */
    public function totalNum() : int
    {
        $result = $this->model
            ->selectRaw('count(id) as count')
            ->where('to', Auth::id())
            ->first();

        return $result->count ?? 0;
    }
}