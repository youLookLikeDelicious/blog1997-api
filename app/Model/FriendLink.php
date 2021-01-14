<?php

namespace App\Model;

use App\Model\Traits\EditAble;
use Illuminate\Database\Eloquent\Model;

class FriendLink extends Model
{
    use EditAble;

    protected $table = 'friend_link';

    protected $dateFormat = 'U';

    protected $guarded = [];
}
