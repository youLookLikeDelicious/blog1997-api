<?php

namespace App\Contract\Repository;

use Illuminate\Http\Request;
/**
 * @method static array getTopTen()
 */
interface Article {
    public function find ($id);

    /**
     * Get article list
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all(Request $request);
}