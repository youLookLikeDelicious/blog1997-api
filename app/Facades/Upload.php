<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Foundation\Upload uploadImage($files, $type, $width = 0, $height = 0, $withWaterMark = true)
 * 
 * @see \App\Foundation\Upload
 */
class Upload extends Facade{
    protected static function getFacadeAccessor(){
        return 'Upload';
    }
}
