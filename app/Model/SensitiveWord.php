<?php

namespace App\Model;

use App\Model\Traits\EditAble;
use Illuminate\Database\Eloquent\Model;

/**
 * 敏感词汇模型
 */
class SensitiveWord extends Model
{
    use EditAble;
    
    public $dateFormat = 'U';

    protected $table = 'sensitive_word';

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_id' => 'integer',
    ];
}
