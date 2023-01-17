<?php
namespace App\Service;

use App\Contract\Repository\Gallery;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class GalleryService
{
    protected $gallery;

    public function __construct(Gallery $gallery)
    {
        $this->gallery = $gallery;
    }

    /**
     * 生成galleryId
     *
     * @return int
     */
    public function calculateGalleryId()
    {
        $galleryId = $this->getNewestArticleGalleryId();

        $nextGallery = $this->gallery->next($galleryId);

        $first = $this->gallery->first();
        
        return $nextGallery
            ? $nextGallery->id
            : ($first ? $first->id : 1);
    }

    /**
     * 获取最新的文章
     *
     * @return int
     */
    protected function getNewestArticleGalleryId()
    {
        $article = Article::select('gallery_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->first();

        return $article ? ($article->gallery_id ?: 0) : 0;
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