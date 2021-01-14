<?php

namespace App\Foundation;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Upload
{
    /**
     * 上传的文件列表
     *
     * @var array
     */
    protected $fileList = [];

    /**
     * 对应的文件的尺寸
     */
    protected $fileSize = [];

    /**
     * 上传图片
     * 
     * @param Illuminate\Http\UploadedFile|array $files 上传的文件列表
     * @param string $type article|gallery
     * @param int $width
     * @param int $height
     * @param boolean $withWaterMark 是否添加水印
     * @return this
     */
    public function uploadImage($files, $type, $width = 0, $height = 0, $withWaterMark = true)
    {
        $filePath = []; // 保存图片全路径的的地址
        $fileSize = [];

        // 生成存储的位置
        $storagePath = $this->createStorageDirectory($type);

        if (!is_array($files)) {
            $files = [$files];
        }

        // 处理图片
        // 将图片转为webp格式，给图片添加水印
        foreach ($files as $v) {
            $img = Image::make($v->getRealPath());

            // 生成文件的名字
            $fileFullName= $this->generateFileFullPath($storagePath) . '.' . $v->getClientOriginalExtension();

            $filePath[] = '/' . $fileFullName;
            $fileSize[] = $this->getImageSize($img);

            // 重置图片大小
            if ($width || $height) {
                $this->resize($img, $width, $height);
            }

            // 添加水印
            if ($withWaterMark) {
                $this->addWaterMark($img);
            }

            $this->putImageToStorage($img, $fileFullName);

            $img->destroy();
        }

        $this->setFileList($filePath, $fileSize);

        return $this;
    }

    /**
     * set file list
     *
     * @param array $list
     * @return void
     */
    protected function setFileList($list, $fileSize)
    {
        $this->fileList = $list;
        $this->fileSize = $fileSize;
    }

    /**
     * 获取上传后的文件列表
     * 
     * @param boolean $withSize
     * @return array
     */
    public function getFileList($withSize = false)
    {
        if (!$withSize) {
            return $this->fileList;
        }

        return array_map(function ($imageName, $sizeInfo) {
            return $imageName . '?' . $sizeInfo;
        }, $this->fileList, $this->fileSize);
    }

    /**
     * Generate file full path
     *
     * @param string $storagePath
     * @return string
     */
    protected function generateFileFullPath($storagePath)
    {
        $prefix = str_replace(' ', '', microtime()) + mt_rand(1, 1000);

        $fileName = uniqid($prefix, true);

        $fileFullPath = $storagePath . $fileName;

        return $fileFullPath;
    }

    /**
     * 为图片添加水印
     * @param \Intervention\Image\Image $img
     * @return void
     */
    protected function addWaterMark($img)
    {
        $height = $img->height();
        $fontSize = ceil($height / 30);
        $x = 30;
        $y = $height > $fontSize ? $height - $fontSize / 2 : $height;

        $img->text('©www.blog1997.com', $x, $y, function ($font) use ($fontSize) {
            $font->file(public_path('GenJyuuGothic-Normal.ttf'));
            $font->size($fontSize);
            $font->color('#ffffff');
        });
    }

    /**
     * 创建存储文件的文件夹
     * 
     * @param string $type
     * @return string
     */
    protected function createStorageDirectory($type)
    {
        $date = date('Y-m-d');
        $storagePath = "image/{$type}/{$date}/";

        return $storagePath;
    }

    /**
     * 重新定义图片的大小
     * @param $img
     * @param $width
     * @param $height
     */
    protected function resize($img, $width, $height)
    {
        if ($width && !$height) {
            $img->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else if (!$width && $height) {
            $img->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else if ($width && $height) {
            $img->fit($width, $height, function ($constraint) {
                // $constraint->upsize();
            });
        }
    }

    /**
     * 备份一个缩略图
     *
     * @param string $width
     * @param string $height
     * @return this
     */
    public function createThumbnail($width = '450', $height = '')
    {
        $fileList = $this->getFileList();

        foreach ($fileList as $file) {

            $image = Image::make(storage_path($file));

            $this->resize($image, $width, $height);

            $ext = strrchr($file, '.');
            $thumbnailFile = str_replace($ext, '.min' . $ext, $file);
            $webpThumbnailFile = str_replace($ext, '.min.webp', $file);

            Storage::put($thumbnailFile, (string) $image->encode());
            Storage::put($webpThumbnailFile, (string) $image->encode('webp'));

            $image->destroy();
        }

        return $this;
    }

    /**
     * 获取图片尺寸信息
     *
     * @param \Intervention\Image\Image $image
     * @return string
     */
    protected function getImageSize($image)
    {
        return "width={$image->width()}&height={$image->height()}";
    }

    /**
     * Put file to storage
     * and back up a webp version
     *
     * @param \Intervention\Image\Image $image
     * @param string $imageFullName
     * @return void
     */
    protected function putImageToStorage($image, $imageFullName)
    {
        Storage::put($imageFullName, (string) $image->encode());

        $webpFullName = str_replace(strrchr($imageFullName, '.'), '.webp', $imageFullName);
        
        Storage::put($webpFullName, (string) $image->encode('webp'));
    }
}
