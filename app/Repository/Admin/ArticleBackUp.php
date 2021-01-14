<?php
namespace App\Repository\Admin;

use App\Model\ArticleBackUp as ModelArticleBackUp;
use App\Contract\Repository\ArticleBackUp as RepositoryArticleBackUp;

class ArticleBackUp implements RepositoryArticleBackUp
{
    /**
     * Eloquent ORM
     *
     * @var App\Model\ArticleBackUp
     */
    protected $model;

    public function __construct(ModelArticleBackUp $model)
    {
        $this->model = $model;
    }

    /**
     * 判断模型是否存在
     *
     * @param int $id
     * @return boolean
     */
    public function exists($id)
    {
        return $this->model->select('id')->find($id);
    }

    /**
     * 获取回收站的文章数量
     *
     * @return int
     */
    public function count() {
        return $this->model->selectRaw('count(id) as count')
            ->where('user_id', auth()->id())
            ->where('delete_role', 'user')
            ->first()
            ->count;
    }

    /**
     * 生成查询回收站文章的Query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function generateQuery () : \Illuminate\Database\Eloquent\Builder {
        return $this->model->select(['id', 'title', 'is_origin', 'visited', 'commented', 'liked', 'updated_at', 'deleted_at', 'user_id'])
            ->where('delete_role', 'user')
            ->where('user_id', auth()->id());
    }
}