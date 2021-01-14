<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface Tag
{
    public function all(?Request $request) : array;
}