<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Contract\Repository\Gallery;
use App\Http\Controllers\Controller;
use App\Model\Gallery as ModelGallery;
use App\Http\Requests\UploadImageRequest;

/**
 * @group Gallery management
 * 
 * 管理相册
 * Gallery Management
 */
class GalleryController extends Controller
{
    /**
     * Get galleries' records
     * 
     * 获取图片列表
     * 
     * @response/admin/gallery/index.json
     * @param Request $request
     * @param Gallery $gallery
     * @return \Illuminate\Http\Response
     */
    public function index (Request $request, Gallery $gallery)
    {
        $result = $gallery->all($request);
        
        return $result->toResponse($request);
    }

    /**
     * Upload images
     * 
     * 上传图片(可批量上传)
     * 允许上传10M以内的图片,会在左下角生成blog1997文字水印
     * 同时会备份一个webp格式的图片和一个等比缩放,宽度为240的缩略图(缩略图在前台懒加载的时候提升用户体验)
     *
     * @bodyParam files   array required 上传的文件列表
     * @bodyParam files.* image required 上传的图片
     * @param UploadImageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request, Gallery $gallery)
    {        
        $gallery->store($request);

        return response()->success('', '图片上传成功');
    }

    /**
     * Get image detail
     * 
     * 获取图片的详情
     *
     * @param Gallery $gallery
     * @return @return \Illuminate\Http\Response
     */
    public function show(ModelGallery $gallery)
    {
        return response()->success($gallery->makeHidden('user_id'));
    }

    /**
     * Remove updated image
     * 
     * 删除上传图片
     * Delete gallery image
     *
     * @urlParam gallery 相册图片的id
     * @responseFile response/admin/gallery/destroy.json
     * @param ModelGallery $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelGallery $gallery)
    {
        $gallery->softDelete();

        return response()->success('', '图片删除成功');
    }
}
