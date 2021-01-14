<?php
namespace App\Contract\Repository;

interface ThumbUp
{
    /**
     * 获取总的点赞量
     *
     * @return int
     */
    public function totalNum() : int;
}