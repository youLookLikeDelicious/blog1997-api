<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //
    public $dateFormat = 'U';
    protected $table = 'article';
    protected $guarded = [];
}
