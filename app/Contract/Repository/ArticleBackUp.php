<?php
namespace App\Contract\Repository;

interface ArticleBackUp
{
    /**
     * 判断模型是否存在
     *
     * @param int $id
     * @return boolean
     */
    public function exists($id);

    /**
     * 获取回收站的文章数量
     *
     * @return int
     */
    public function count();

    /**
     * 生成查询回收站文章的Query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function generateQuery () : \Illuminate\Database\Eloquent\Builder;
}