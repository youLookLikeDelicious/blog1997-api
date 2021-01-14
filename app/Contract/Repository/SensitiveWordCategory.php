<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface SensitiveWordCategory
{
    public function all(Request $request);
}