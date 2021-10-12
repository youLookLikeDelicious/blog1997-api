<?php
namespace App\Facades\Service;

use Illuminate\Support\Facades\Facade;

/**
 * Asa 加密|解密
 * 
 * @method static string decrypt ($data)
 * @see \App\Service\RSAService
 */
class RSAService extends Facade
{
    protected static function getFacadeAccessor(){
        return 'RSAService';
    }
}