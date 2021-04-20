<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * 集中管理缓存数据
 * 
 * @method static void getSystemSetting()     获取系统配置缓存
 * @method static void forgetSystemSetting()  清除系统配置缓存
 */
class CacheModel extends Facade
{
    protected static function getFacadeAccessor(){
        return 'CacheModel';
    }
}