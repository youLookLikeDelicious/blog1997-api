<?php

namespace App\Http\Controllers\Admin;

use App\Facades\Upload;
use App\Contract\Repository\Gallery;
use App\Http\Controllers\Controller;
use App\Model\Gallery as ModelGallery;
use App\Http\Requests\UploadImageRequest;
use App\Service\UrlConvertToGalleryService;

class GalleryController extends Controller
{
    /**
     * 获取图片列表
     * 
     * Method GET
     * @return \Illuminate\Http\Response
     */
    public function index (Gallery $gallery)
    {
        $result = $gallery->all();
        
        return response()->success($result);
    }

    /**
     * 上传图片
     *
     * @param UploadImageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        // 获取文件
        $data = $request->validated();
        
        // 开始上传
        $upload = Upload::uploadImage($data['files'], 'gallery')
            ->createThumbnail('240');

        $result = $upload->getFileList();

        $files = (new UrlConvertToGalleryService())->make($result);

        $galleries = [];

        foreach($files as $file) {
            $galleries[] = ModelGallery::create($file);
        }

        return response()->success($galleries, '图片上传成功');
    }

    /**
     * Delete gallery image
     *
     * @param ModelGallery $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelGallery $gallery)
    {
        $gallery->delete();

        return response()->success('', '图片删除成功');
    }
}
