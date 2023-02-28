<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    const TYPE_MAP = [
        1 => '微信',
        2 => 'github',
        3 => 'QQ'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'social_accounts';

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'foreign_id'];

    /**
     * Define many to one relation with user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 获取第三方账号类型文本
     *
     * @return string
     */
    public function getTypeTextAttribute()
    {
        return static::TYPE_MAP[$this->type];
    }
}
