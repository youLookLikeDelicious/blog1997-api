<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface SensitiveWordCategory
{
    /**
     * 分页获取所有分类
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all(Request $request);

    /**
     * 部分页获取所有分类
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list();
}