<?php

namespace App\Model;

use App\Events\GalleryCreateEvent;
use App\Events\GalleryDeletedEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Gallery extends Model
{
    use SoftDeletes;

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
}
