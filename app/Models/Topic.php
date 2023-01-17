<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{    
    use HasFactory;
    
    public $dateFormat = 'U';

    protected $table = 'topic';

    protected $guarded = ['created_at', 'updated_at'];
}
