<?php

namespace App\Http\Controllers\Upload;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;

class UploadController extends Controller
{
    /**
     * 上传图片
     * @return Array
     */
    public function uploadImage (Request $request) {
        // 获取上传的文件
        $data = [];
        $data['files'] = $request->file('upfile');
        $id = $request['id']; // 图片唯一的表示

        $fileFullpathArr = []; // 保存图片全路径的的地址

        // 验证图片
        $validator = $this->validator($data);
        if ($validator->fails()) {
            return response()->error($validator->errors());
        }

        // 生成存储的位置
        $storagePath = ('image/'.date('Y-m-d') . '/');
        if (!is_dir(storage_path($storagePath))) {
            mkdir(storage_path($storagePath), 0777, true);
        }
        // 处理图片
        // 将图片转为webp格式，给图片添加水印
        foreach ($data['files'] as $key => $v) {
            $img = Image::make($v->getRealPath());
            $height = $img->height();
            $fontSize = ceil($height / 40);
            $x = 30;
            $y = $height > $fontSize? $height - $fontSize / 2 : $height;

            // 添加水印
            $img->text('www.blog1997.com', $x, $y, function ($font) use ($fontSize) {
               $font->file(public_path('GenJyuuGothic-Normal.ttf'));
               $font->size($fontSize);
               $font->color('#ffffff');
            });

            // 获取文件的扩展类型
            $ext = $v->getClientOriginalExtension();

            // 生成文件的名字
            $prefix = str_replace(' ', '', microtime()) + mt_rand(1, 1000);
            $fileName = uniqid($prefix, true);

            $fileFullpath = $storagePath.$fileName;

            // 保存图片，同时生成一份webp格式的文件
            $savePath = storage_path($fileFullpath);
            $img->save($savePath.'.'.$ext, 50, $ext);
            $img->save($savePath.'.webp', 40, 'webp');

            $fileFullpathArr[$id[$key]] = $request->root(). '/' .str_replace(storage_path(), '', $fileFullpath).'.'.$ext;
        }

        return response()->success($fileFullpathArr);
    }

    /**
     * 验证图片的大小和格式
     * @param $data
     */
    protected function validator ($data) {
        $message = [
            'files.*.required' => '暂无图片上传',
            'files.*.image' => '未识别的图片格式',
            'files.*.max' => '图片文件不能超过:maxKB'
        ];
        $rule = [
            'files.*' => 'required|image|max:3072' // 文件最大为3M
        ];
        $validator = Validator::make($data, $rule, $message);

        return $validator;
    }
}
