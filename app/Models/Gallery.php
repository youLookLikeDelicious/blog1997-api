<?php

namespace App\Models;

use App\Events\GalleryCreateEvent;
use App\Events\GalleryDeletedEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'gallery';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleting' => GalleryDeletedEvent::class,
        'created' => GalleryCreateEvent::class
    ];

    /**
     * Generate resource url
     *
     * @param string $val
     * @return string
     */
    public function getUrlAttribute($val)
    {
        return URL::asset($val);
    }

    /**
     * Format unix time stamp
     *
     * @param int $val
     * @return string
     */
    public function getDateTimeAttribute($val)
    {
        return $val ? date('Y-m-d H:i', $val) : '';
    }

    public function album()
    {
        return $this->belongsToMany(Album::class, 'gallery_album');
    }
}
