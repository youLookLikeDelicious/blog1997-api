<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Curl封装
 * 
 * @method static array make ($url, array $opt, boolean $getJson)
 */
class CurlService extends Facade
{
    protected static function getFacadeAccessor(){
        return 'CurlService';
    }
}