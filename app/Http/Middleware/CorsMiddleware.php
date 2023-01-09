<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $originList = [
            // 'http://localhost:9090'
        ];
        /* 记录$_SERVER/
        $log = '';
        foreach ($_SERVER as $k => $v) {
            $log .= $k . '  =  ' . $v . "\r\n";
        }
        $log .= "====================================================\r\n";

        file_put_contents(storage_path('log.txt'), $log, FILE_APPEND);
        //*/
        if (!isset($_SERVER['HTTP_ORIGIN'])) {
            $_SERVER['HTTP_ORIGIN'] = '';
        }
        
        // 获取origin信息
        if (in_array($_SERVER['HTTP_ORIGIN'], $originList)) {
            header('Access-Control-Allow-Origin:' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Credentials:true');
            header('Access-Control-Allow-Methods:POST, GET, OPTIONS, PUT, DELETE');
            header('Access-Control-Allow-Headers:content-type,X-CSRF-TOKEN,support-webp,X-Forwarded-For,X-Real-IP,x-xsrf-token,X-Requested-With,Cookie');
        }
        
        $GLOBALS['startTime'] = microtime(true);

        return $next($request);
    }
}
