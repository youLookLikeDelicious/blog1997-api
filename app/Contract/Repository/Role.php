<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface Role
{
    /**
     * 分页获取所有的权限
     *
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all(Request $request);

    public function flatted();
}