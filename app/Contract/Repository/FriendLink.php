<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface FriendLink
{
    /**
     * Get friend link list
     *
     * @param Request|null $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function all (?Request $request = null);
}