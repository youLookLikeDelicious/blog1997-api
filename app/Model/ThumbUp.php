<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ThumbUp extends Model
{
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
