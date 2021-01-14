<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface FriendLink
{
    public function all (?Request $request = null);
}