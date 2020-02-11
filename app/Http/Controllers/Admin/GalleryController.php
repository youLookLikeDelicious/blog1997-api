<?php

namespace App\Http\Controllers\Admin;

use App\Model\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    /**
     * 上传图片
     * Method POST
     * @param Request $request
     * @return mixed
     */
    public function upload (Request $request) {
        // 获取文件
        $files = $request->file('upfile');

        // 开始上传
        $upload = \Upload::uploadImage($files, 'gallery', 820);

        if ($upload->errors()) {
            return response()->error($upload->errors());
        }

        // 获得返回的结果,将数据的键值改为url
        $files = $upload->getFileList();
        $files = array_combine(array_fill(0, count($files), 'url'), $files);

        // 入库操作
        $result = Gallery::insert($files);

        if ($result) {
            return response()->success(null, '图片上传成功');
        } else {
            // 上传失败，需要删除相关文件
            return response()->error('图片上传失败');
        }
    }

    /**
     * 获取图片列表
     * Method GET
     * @param Request $request
     * @return mixed
     */
    public function getList (Request $request) {
        $galleryQuery = Gallery::select('id', 'url', 'created_at');
        $result = \Page::paginate($galleryQuery);

        return response()->success($result);
    }
}
