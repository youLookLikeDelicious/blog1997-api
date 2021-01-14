<?php
namespace App\Service;
use Illuminate\Support\Facades\Auth;
class UrlConvertToGalleryService
{
    /**
     * 将url转为 gallery Eloquent模型 insert时需要的数据
     *
     * @param array $urls
     * @return array
     */
    public function make(array $urls) : array
    {
        return array_map(function ($url) {
            return [
                'url' => $url,
                'created_at' => time(),
                'updated_at' => time()
            ];
        }, $urls);
    }
}