<?php
namespace App\Repository\Home;

use App\Models\FriendLink as ModelFriendLink;
use App\Contract\Repository\FriendLink as RepositoryFriendLink;
use Illuminate\Http\Request;

class FriendLink implements RepositoryFriendLink
{
    protected $friendLink;

    public function __construct(ModelFriendLink $modelFriendLink)
    {
        $this->friendLink = $modelFriendLink;
    }

    /**
     * 获取所有的友链
     *
     * @return array
     */
    public function all(?Request $request = null)
    {
        return $this->friendLink->select(['id', 'name', 'url'])
            ->get()
            ->toArray();
    }
}