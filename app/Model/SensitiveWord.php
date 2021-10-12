<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 敏感词汇模型
 */
class SensitiveWord extends Model
{    
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
        'created_at' => 'datetime:Y-m-d H:i',
    ];

    /**
     * Define relationship with category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(SensitiveWordCategory::class);
    }
}
