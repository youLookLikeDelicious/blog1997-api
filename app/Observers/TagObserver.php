<?php

namespace App\Observers;

use App\Model\Tag;
use Illuminate\Support\Facades\Storage;

class TagObserver
{
    /**
     * Handle the tag "updated" event.
     *
     * @param  \App\Model\Tag  $tag
     * @return void
     */
    public function updated(Tag $tag)
    {
        $cover = $tag->getOriginal('cover');
        
        if ($tag->isDirty('cover') && $cover) {
            $webpCover = str_replace(strrchr($cover, '.'), '.webp', $cover);
            Storage::delete([$cover, $webpCover]);
        }
    }

    /**
     * Handle the tag "deleted" event.
     *
     * @param  \App\Model\Tag  $tag
     * @return void
     */
    public function deleted(Tag $tag)
    {
        if ($tag->cover) {        
            // 同时删除webp的备份
            $ext = strrchr($tag->cover, '.');
            $webpCover = str_replace($ext, '.webp', $tag->cover);
            Storage::delete([$tag->cover, $webpCover]);
        }
    }
}
