<?php

namespace App\Foundation;
use Validator;
use Intervention\Image\ImageManagerStatic as Image;

class Upload
{
    protected $errors = null; // 错误信息
    protected $fileList = []; // 上传成功的文件列表

    /**
     * 上传图片
     * @param $files 上传的文件列表
     * @param $type article|gallry
     * @param int $width
     * @param int $height
     * @return Array
     */
    public function uploadImage ($files, $type, $width = 0, $height = 0) {
        $filePath = []; // 保存图片全路径的的地址
        $host = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['HTTP_HOST']}/";

        if (!$files) {
            return response()->error('暂无图片上传');
        }

        // 验证图片
        $validator = $this->validator($files);
        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return $this;
        }

        // 生成存储的位置
        $storagePath = $this->makeDir($type);

        // 处理图片
        // 将图片转为webp格式，给图片添加水印
        foreach ($files as $key => $v) {
            $img = Image::make($v->getRealPath());

            // 添加水印
            $this->addWaterMark($img);

            // 获取文件的扩展类型
            $ext = $v->getClientOriginalExtension();

            // 生成文件的名字
            $prefix = str_replace(' ', '', microtime()) + mt_rand(1, 1000);
            $fileName = uniqid($prefix, true);

            $fileFullPath = $storagePath.$fileName;

            // 保存图片，同时生成一份webp格式的文件
            $savePath = storage_path($fileFullPath);

            // 重置图片大小
            if ($width || $height) {
                $this->resize($img, $width, $height);
            }

            $img->save($savePath.'.'.$ext, 40, $ext);
            $img->save($savePath.'.webp', 20, 'webp');

            $filePath[] = $host . str_replace(storage_path(), '', $fileFullPath).'.'.$ext;
        }

        $this->fileList = $filePath;

        return $this;
    }

    /**
     * 验证图片的大小和格式
     * @param $data
     * @return
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

    /**
     * 为图片添加水印
     * @param $img
     */
    protected function addWaterMark ($img) {
        $height = $img->height();
        $fontSize = ceil($height / 40);
        $x = 30;
        $y = $height > $fontSize? $height - $fontSize / 2 : $height;

        $img->text('www.blog1997.com', $x, $y, function ($font) use ($fontSize) {
            $font->file(public_path('GenJyuuGothic-Normal.ttf'));
            $font->size($fontSize);
            $font->color('#ffffff');
        });
    }

    /**
     * 创建存储文件的文件夹
     * @param $type
     * @return string
     */
    protected function makeDir ($type) {
        $date = date('Y-m-d');
        $storagePath = ("image/{$type}/{$date}/");
        if (!is_dir(storage_path($storagePath))) {
            mkdir(storage_path($storagePath), 0777, true);
        }

        return $storagePath;
    }
    /**
     * 验证是否通过
     */
    public function errors () {
        return $this->errors;
    }

    /**
     * 获取上传后的文件列表
     * @return array
     */
    public function getFileList () {
        return $this->fileList;
    }

    /**
     * 重新定义图片的大小
     * @param $img
     * @param $width
     * @param $height
     */
    protected function resize ($img, $width, $height) {
        if ($width && !$height) {
            $img->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else if (!$width && $height) {
            $img->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else if ($width && $height) {
            $img->resize($width, $height);
        }
    }
}
