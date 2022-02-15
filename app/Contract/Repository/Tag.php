<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

/**
 * 返回全部的标签列表
 */
interface Tag
{
    public function all(?Request $request);
}