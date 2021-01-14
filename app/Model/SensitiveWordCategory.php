<?php

namespace App\Model;

use App\Model\Traits\EditAble;
use Illuminate\Database\Eloquent\Model;

/**
 * 敏感词汇分类模型
 */
class SensitiveWordCategory extends Model
{
    use EditAble;
    
    public $dateFormat = 'U';

    protected $table = 'sensitive_word_category';

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rank' => 'int'
    ];
}
