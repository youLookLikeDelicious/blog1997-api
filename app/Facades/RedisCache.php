<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * 集中管理redis缓存
 */
class RedisCache extends Facade{
    protected static function getFacadeAccessor(){
        return 'RedisCache';
    }
}
