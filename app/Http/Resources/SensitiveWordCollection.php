<?php

namespace App\Http\Resources;

use App\Contract\Repository\SensitiveWordCategory;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SensitiveWordCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($word) {
            return [
                'id' => $word->id,
                'word' => $word->word,
                'category_id' => $word->category_id,
                'category_name' => $word->category->name,
                'rank_text' => $word->category->rankText,
                'created_at' => $word->created_at->format('Y-m-d H:i')
            ];
        });
    }

    public function with($request)
    {
        return [
            'message' => 'success',
            'meta' => [
                'categoryList' => app()->make(SensitiveWordCategory::class)->list()
            ]
        ];
    }
}
