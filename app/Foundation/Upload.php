<?php

namespace App\Foundation;

use App\Facades\MapService;
use App\Facades\ImageSampler;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Upload
{
    /**
     * 上传的文件列表
     *
     * @var \Illuminate\Support\Collection
     */
    protected $fileList = [];

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
        $fileList = [];

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

            $fileList[] = [
                'url' => '/' . $fileFullName,
                'size' => $this->getImageSize($img),
                'exif' => $img->exif(null, true)
            ];

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

        $this->setFileList($fileList);

        return $this;
    }

    /**
     * set file list
     *
     * @param array $list
     * @return void
     */
    protected function setFileList($list)
    {
        $this->fileList = collect($list);
    }

    /**
     * 获取上传后的文件列表
     * 
     * @param boolean $withSize 是否在url中返回图片尺寸
     * @param boolean $withExtraInfo 是否返回额外的信息
     * @return array
     */
    public function getFileList($withSize = false, $withExtraInfo = false)
    {
        $fileList = [];

        if ($withSize) {
            $fileList = $this->fileList->map(function ($file) {
                return $file['url'] . '?' . $file['size'];
            });
        } else if ($withExtraInfo) {
            $fileList = $this->getFileListWithExif();
        } else {
            $fileList = $this->fileList->pluck('url');
        }

        return $fileList;
    }

    /**
     * 获取文件列表,并返回相关的exif信息
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getFileListWithExif()
    {
        $fileList = $this->fileList->map(function ($file) {
            
            $gps = $this->getImageLocation($file['exif']);

            $location = MapService::decodeLocation($gps);

            $colors = ImageSampler::make(storage_path($file['url']))->sample()
                ->map(function ($color) {
                    return implode(';', $color);
                })->join(';');

            return [
                'lng_lat'       => $gps ?: '',
                'colors'        => $colors,
                'url'           => $file['url'],
                'camera_name'   => $file['exif']['Make'] ?? '',
                'exposure_time' => $file['exif']['ExposureTime'] ?? '',
                'f_number'      => $file['exif']['FNumber'] ?? '',
                'focal_length'  => $file['exif']['FocalLength'] ?? '',
                'location'      => $location ? $location->regeocode->formatted_address : '',
                'date_time'     => empty($file['exif']['DateTime']) ? 0 : Carbon::parse($file['exif']['DateTime'])->timestamp,
                'created_at'    => time(),
                'updated_at'    => time()
            ];
        });
        return $fileList;
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

        $realStoragePath = storage_path($storagePath);
        if (!is_dir($realStoragePath)) {
            mkdir($realStoragePath, 0777, true);
        }
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
    public function createThumbnail($width = '100', $height = '')
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
        $image->save(storage_path($imageFullName), 100);

        $webpFullName = str_replace(strrchr($imageFullName, '.'), '.webp', $imageFullName);
        
        Storage::put($webpFullName, (string) $image->encode('webp'));
    }

    /**
     * 获取图片的经纬度
     * 
     * Returns an array of latitude and longitude from the Image file
     * @param $exif image exif info
     * @return mixed:array|boolean
     */
    function getImageLocation($exif){
        if(!$exif || empty($exif['GPSLatitudeRef']) || empty($exif['GPSLatitude']) || empty($exif['GPSLongitudeRef']) || empty($exif['GPSLongitude'])) {
            return false;
        }

        $GPSLatitudeRef = $exif['GPSLatitudeRef'];
        $GPSLatitude    = $exif['GPSLatitude'];
        $GPSLongitudeRef= $exif['GPSLongitudeRef'];
        $GPSLongitude   = $exif['GPSLongitude'];
        
        $lat_degrees = count($GPSLatitude) > 0 ? $this->gps2Num($GPSLatitude[0]) : 0;
        $lat_minutes = count($GPSLatitude) > 1 ? $this->gps2Num($GPSLatitude[1]) : 0;
        $lat_seconds = count($GPSLatitude) > 2 ? $this->gps2Num($GPSLatitude[2]) : 0;
        
        $lon_degrees = count($GPSLongitude) > 0 ? $this->gps2Num($GPSLongitude[0]) : 0;
        $lon_minutes = count($GPSLongitude) > 1 ? $this->gps2Num($GPSLongitude[1]) : 0;
        $lon_seconds = count($GPSLongitude) > 2 ? $this->gps2Num($GPSLongitude[2]) : 0;
        
        $lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
        $lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;
        
        $latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60*60)));
        $longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60*60)));

        return $longitude . ',' . $latitude;
    }

    function gps2Num($coordPart){
        $parts = explode('/', $coordPart);
        if(count($parts) <= 0)
        return 0;
        if(count($parts) == 1)
        return $parts[0];
        return floatval($parts[0]) / floatval($parts[1]);
    }
}
