<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FriendLink extends Model
{
    //
    protected $table = 'friend_link';
    protected $dateFormat = 'U';
    protected $guarded = [];

    protected $appends = ['edit_state'];

    /**
     * 给模型定义额外的属性 edit_state
     * @return bool
     */
    public function getEditStateAttribute () {
        return false;
    }
}
