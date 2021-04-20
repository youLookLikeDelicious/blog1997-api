<?php
namespace App\Contract\Repository;

/**
 * @method public array paginate() 后台分页获取专题
 */
interface Topic{
    public function all();
}