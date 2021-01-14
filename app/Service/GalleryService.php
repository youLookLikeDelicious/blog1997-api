<?php
namespace App\Service;

use App\Contract\Repository\Article;
use App\Contract\Repository\Gallery;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class GalleryService
{
    protected $article;
    protected $gallery;

    public function __construct(Article $article, Gallery $gallery)
    {
        $this->article = $article;

        $this->gallery = $gallery;
    }

    /**
     * 生成galleryId
     *
     * @return int
     */
    public function calculateGalleryId()
    {
        $galleryId = $this->article->getNewestArticleGalleryId();

        $nextGallery = $this->gallery->next($galleryId);

        $first = $this->gallery->first();
        
        return $nextGallery ? $nextGallery->id : 
            ($first ? $first->id : 1);
    }

    /**
     * 生成base64格式的缩略图
     * 
     * @param string $filePath
     * @return string
     */
    public function createTinyThumbnail($filePath)
    {
        $fileFullName = storage_path($filePath);

        if (!Storage::exists($filePath)) {
            return '';
        }

        $image = Image::make($fileFullName);
        
        $base64Image = $image->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })
            ->blur(7)
            ->encode('data-url');

        $image->destroy();

        return $base64Image;
    }
}