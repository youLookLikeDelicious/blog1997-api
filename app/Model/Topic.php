<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{    
    public $dateFormat = 'U';

    protected $table = 'topic';

    protected $guarded = ['created_at', 'updated_at'];
}
