<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 记录违规的评论信息
 */
class IllegalComment extends Model
{
    use HasFactory;
    
    protected $table = 'illegal_comment';
    protected $dateFormat = 'U';
    protected $guarded = [];
}
