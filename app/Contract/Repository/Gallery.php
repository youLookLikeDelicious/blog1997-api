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
     * 获取所有图片
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function list (Request $request);

    /**
     * 获取下一张相册的数据
     *
     * @return \App\Models\Gallery
     */
    public function next(int $id);

    /**
     * 获取第一张相册的数据
     *
     * @return \App\Models\Gallery
     */
    public function first();

    /**
     * 上传图片
     *
     * @param \App\Http\Requests\UploadImageRequest $request
     * @return void
     */
    public function store($request);

    /**
     * 获取所有相册
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function albumAll();

    /**
     * 分页获取所有
     *
     * @return CommonCollection
     */
    public function albumList($request);
}