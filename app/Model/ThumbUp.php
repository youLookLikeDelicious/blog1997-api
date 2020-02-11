<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ThumbUp extends Model
{
    //
    public $dateFormat = 'U';
    protected $table = 'thumb_up';
    protected $guarded = [];
}
