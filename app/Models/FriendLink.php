<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendLink extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    protected $table = 'friend_link';

    protected $dateFormat = 'U';
}
