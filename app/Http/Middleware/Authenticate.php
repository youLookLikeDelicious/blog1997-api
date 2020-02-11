<?php

namespace App\Http\Middleware;

use Closure;
use App\Facades\CustomAuth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = CustomAuth::user();

        if (!$user) {
            return response()->error('用户信息异常，请重新登陆', 401);
        }

        $request->request->add(['user_id' => $user->id]);

        return $next($request);
    }
}
