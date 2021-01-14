<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 记录违规的评论信息
 */
class IllegalComment extends Model
{
    protected $table = 'illegal_comment';
    protected $dateFormat = 'U';
    protected $guarded = [];
}
