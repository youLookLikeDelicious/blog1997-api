<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThumbUp extends Model
{
    use HasFactory;
    
    public $dateFormat = 'U';
    protected $table = 'thumb_up';
    protected $guarded = [];

    /**
     * Define thumb morphy relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function thumbable()
    {
        return $this->morphTo('thumbable', 'able_type', 'able_id');
    }
}
