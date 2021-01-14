<?php

namespace App\Model;

use App\Model\Traits\EditAble;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use EditAble;
    
    public $dateFormat = 'U';

    protected $table = 'topic';

    protected $guarded = [];

    // protected $keyType = 'string';
}
