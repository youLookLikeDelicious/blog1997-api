<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string make($domString, $isMarkdown = false) 高亮code代码
 * 
 * @see \App\Foundation\HighLightHtml
 */
class HighLightHtml extends Facade
{
    protected static function getFacadeAccessor(){
        return 'HighLightHtml';
    }
}
