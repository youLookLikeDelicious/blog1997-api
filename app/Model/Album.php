<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    protected $table = 'album';

    protected $dateFormat = 'U';

    public function galleries()
    {
        return $this->belongsToMany(Gallery::class, 'gallery_album', 'album_id', 'gallery_id');
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
