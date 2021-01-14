<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface SensitiveWord
{
    /**
     * 获取所有的 word字段
     * 
     * @return array
     */
    public function getWordList () : array;

    /**
     * 分页查找记录
     * @param int $categoryId
     * 
     * @return array
     */
    public function all (Request $request) : array;
}