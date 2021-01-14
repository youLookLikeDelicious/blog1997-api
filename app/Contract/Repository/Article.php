<?php

namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface Article {
    public function find ($id);
    public function all(Request $request) : array;
}