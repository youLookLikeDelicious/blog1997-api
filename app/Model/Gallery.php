<?php

namespace App\Model;

use App\Events\GalleryCreateEvent;
use App\Events\GalleryDeletedEvent;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
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
}
