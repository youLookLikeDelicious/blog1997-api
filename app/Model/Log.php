<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $table = 'log';

    protected $guarded = ['id'];

    /**
     * Define relation with user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'avatar' => '',
            'name' => 'system'
        ]);
    }
}
