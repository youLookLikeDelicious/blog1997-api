<?php
namespace App\Contract\Repository;

/**
 * @method public \Illuminate\Http\Resources\Json\ResourceCollection paginate() 后台分页获取专题
 */
interface Topic{
    public function all();
}