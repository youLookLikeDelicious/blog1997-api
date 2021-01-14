<?php

namespace App\Contract\Repository;

interface Gallery {
    public function count() : int;
    public function all () : array;

    /**
     * 获取下一张相册的数据
     *
     * @return \App\Model\Gallery
     */
    public function next(int $id);

    /**
     * 获取第一张相册的数据
     *
     * @return \App\Model\Gallery
     */
    public function first();
}