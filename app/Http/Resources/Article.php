<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Article extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $article = parent::toArray($request);
        $article['cover'] = $article['gallery'] ? $article['gallery']['url'] : '';

        unset($article['gallery_id']);
        unset($article['gallery']);
        
        return $article;
    }
}
