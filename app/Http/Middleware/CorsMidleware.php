<?php

namespace App\Http\Middleware;

use Closure;

class CorsMidleware
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
            'http://localhost:3001'
        ];
        // 获取origin信息
        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $originList)) {
            header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Headers:content-type');
            header('Access-Control-Allow-Credentials:true');
        }
        return $next($request);
    }
}
