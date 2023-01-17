<?php

namespace App\Observers;

use App\Models\Tag;
use Illuminate\Support\Facades\Storage;

class TagObserver
{
    /**
     * Handle the tag "updated" event.
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function updated(Tag $tag)
    {
        $cover = $tag->getOriginal('cover');
        
        if ($tag->isDirty('cover') && $cover) {
            $this->removeCover($cover);
        }
    }

    /**
     * Handle the tag "deleted" event.
     * 
     * 移除标签的封面
     * 
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function deleted(Tag $tag)
    {
        if ($tag->cover) {
            $this->removeCover($tag->cover);
        }
    }

    /**
     * 尝试从本地移除标签的封面
     *
     * @param string $cover 封面地址
     * @return void
     */
    protected function removeCover($cover)
    {
        $webpCover = str_replace(strrchr($cover, '.'), '.webp', $cover);
        Storage::delete([$cover, $webpCover]);
    }
}
