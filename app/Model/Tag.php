<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $dateFormat = 'U';
    protected $table = 'tags';
    protected $guarded = ['id'];
}
