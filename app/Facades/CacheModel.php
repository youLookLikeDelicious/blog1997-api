<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CacheModel extends Facade
{
    protected static function getFacadeAccessor(){
        return 'CacheModel';
    }
}