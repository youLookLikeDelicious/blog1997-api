<?php

namespace App\Http\Middleware;

use Closure;
use App\Jobs\GenerateSiteMap;

class SiteMap
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $priority 优先级 0 - 1
     * @param  $frequent 更新频率
     * @param  $twoLevelPath 二级地图的地址 默认自动生成
     * @return mixed
     */
    public function handle($request, Closure $next, $priority = 0.9, $frequent = 'daily', $twoLevelPath = '')
    {
        // $request->path e.g. api/article/1 get参数不会获取
        GenerateSiteMap::dispatch(str_replace('api', '', $request->path()), $priority, $frequent, $twoLevelPath);
            // ->delay(now()->addMinutes(0));

        return $next($request);
    }
}
