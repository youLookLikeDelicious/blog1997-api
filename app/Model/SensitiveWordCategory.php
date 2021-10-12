<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 敏感词汇分类模型
 */
class SensitiveWordCategory extends Model
{    
    public $dateFormat = 'U';
    
    protected $guarded = [];

    protected $table = 'sensitive_word_category';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rank' => 'int'
    ];

    public function getRankTextAttribute()
    {
        switch ($this->rank) {
            case 1:
                return '替换';
            case 2:
                return '审批';
            case 3:
                return '屏蔽';
            default:
                return '-';
        }
    }
}
