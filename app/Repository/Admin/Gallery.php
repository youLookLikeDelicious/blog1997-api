<?php
namespace App\Repository\Admin;

use App\Facades\Page;
use App\Model\Gallery as ModelGallery;
use App\Contract\Repository\Gallery as RepositoryGallery;

class Gallery implements RepositoryGallery
{
    /**
     * gallery Model
     * @var App\Model\Gallery
     */
    protected $gallery;

    public function  __construct(ModelGallery $gallery)
    {
        $this->gallery = $gallery;
    }

    /**
     * 获取相册的数量
     *
     * @return int
     */
    public function count() : int
    {
        $model = $this->gallery->selectRaw('count(id) as count')->first();

        return $model ? $model->count : 0;
    }

    /**
     * 分页获取数据
     *
     * @return array
     */
    public function all() : array
    {
        $galleryQuery = $this->gallery
            ->select('id', 'url', 'created_at')
            ->where('is_cover', 'no');

        return  Page::paginate($galleryQuery);
    }

    /**
     * 获取下一张封面
     *
     * @param int $id
     * @return ModelGallery
     */
    public function next(int $id)
    {
        $gallery = $this->gallery
            ->where('id', '>', $id)
            ->where('is_cover', 'no')
            ->first();

        return $gallery;
    }

    /**
     * 获取第一张图片的id
     *
     * @return ModelGallery
     */
    public function first()
    {
        $gallery = $this->gallery->first();

        return $gallery;
    }
}