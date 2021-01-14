<?php
namespace App\Repository;

use App\Contract\Repository\IllegalComment as RepositoryIllegalComment;
use App\Model\IllegalComment as ModelIllegalComment;

class IllegalComment implements RepositoryIllegalComment
{
    /**
     * eloquent model
     *
     * @var [type]
     */
    protected $model;

    public function __construct(ModelIllegalComment $model)
    {
        $this->model = $model;
    }

    /**
     * 查找记录是否存在
     *
     * @param int $id
     * @return App\Model\IllegalComment
     */
    public function isEmpty($id)
    {
        return !$this->model->select('id')->find($id);
    }
}