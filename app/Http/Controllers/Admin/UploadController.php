<?php

namespace App\Http\Controllers\Admin;

use App\Facades\Upload;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;

class UploadController extends Controller
{
    /**
     * Undocumented function
     *
     * @param App\Http\Requests\UploadImageRequest $request
     * @param string $category
     * @return Response
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
