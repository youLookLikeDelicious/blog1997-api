<?php

namespace App\Contract\Repository;

use Illuminate\Http\Request;
/**
 * @method array getTopTen() 获取前热度10文章
 */
interface Article {
    public function find ($id);

    /**
     * Get article list
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all(Request $request);
}