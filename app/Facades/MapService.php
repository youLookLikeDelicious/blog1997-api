<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 封装地图的一些接口
 * 
 * @method static json getLocationByIp(string $ip) 根据ip获取地理位置
 * @method static json decodeLocation(string $location) 根据经纬度获取位置信息
 */
class MapService extends Facade
{
    protected static function getFacadeAccessor(){
        return 'MapService';
    }
}