<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface Auth
{
    /**
     * 分页获取所有的权限
     *
     * @param Request $request
     * @return array
     */
    public function all(Request $request);

    /**
     * 获取所有的权限,不进行分页
     *
     * @return array
     */
    public function flatted();

    /**
     * 获取所有权限的 route_name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function routeNames();
}