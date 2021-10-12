<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ImageSampler extends Facade
{
    protected static function getFacadeAccessor(){
        return 'ImageSampler';
    }
}
