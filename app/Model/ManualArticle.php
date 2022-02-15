<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualArticle extends Model
{
    use SoftDeletes;
    
    public $dateFormat = 'U';

    protected $guarded = [];
}
