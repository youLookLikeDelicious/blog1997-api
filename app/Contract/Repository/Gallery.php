<?php

namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface Gallery {
    public function count() : int;

    /**
     * 获取所有图片
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all (Request $request);

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

    /**
     * 上传图片
     *
     * @param \App\Http\Requests\UploadImageRequest $request
     * @return void
     */
    public function store($request);
}