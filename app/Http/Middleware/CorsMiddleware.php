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
            'https://www.blog1997.com',
            // 'http://localhost:1997'
        ];
        /* 记录$_SERVER/
        $log = '';
        foreach ($_SERVER as $k => $v) {
            $log .= $k . '  =  ' . $v . "\r\n";
        }
        $log .= "====================================================\r\n";

        file_put_contents(storage_path('log.txt'), $log, FILE_APPEND);
        //*/

        // 获取origin信息
        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $originList)) {
            header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Credentials:true');
            header('Access-Control-Allow-Headers:content-type,x-xsrf-token,support-webp,X-Forwarded-For,X-Real-IP');
        }
        
        $GLOBALS['startTime'] = microtime(true);

        return $next($request);
    }
}
