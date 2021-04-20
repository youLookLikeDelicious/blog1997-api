<?php

namespace App\Http\Controllers\Admin;

use App\Facades\Upload;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;

/**
 * @group general
 */
class UploadController extends Controller
{
    /**
     * Upload some image
     * 
     * 上传图片
     * 并且会生成对应的webp格式的图片和一个等比缩放,宽度为100的缩略图
     *
     * @bodyParams files array required 图片列表,最大图片为10M
     * @responseFile response/general/upload-image.json
     * @param \App\Http\Requests\UploadImageRequest $request
     * @param string $category 图片属性,例如:avatar,article,gallery
     * @return \Illuminate\Http\Response
     */
    public function uploadImage (UploadImageRequest $request, string $category)
    {
        // 获取文件
        $data = $request->validated();
        
        // 开始上传
        $upload  = Upload::uploadImage($data['files'], $category)
            ->createThumbnail();

        $result = $upload->getFileList($request->input('with-size') == 1);

        return response()->success($result, '图片上传成功');
    }
}
