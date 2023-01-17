<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Tag extends Model
{
    use HasFactory;
    
    public $dateFormat = 'U';
    protected $table = 'tags';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
    ];
    
    /**
     * Convert tag cover to url asset
     *
     * @param string $value
     * @return string
     */
    public function getCoverAttribute($value)
    {
        return URL::asset($value);
    }

    /**
     * Define relationship with child tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
