<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use App\Contract\Repository\Auth;

/**
 * Rbac Middleware
 * @author chaos
 */
class RoleBaseAccessControl
{
    /**
     * Authority repository
     *
     * @var Auth
     */
    protected $authorities;

    function __construct(Auth $authorities)
    {
        $this->authorities = $authorities;
    }

    public function handle($request, Closure $next)
    {
        if ($this->allowAccess()) {
            return $next($request);
        }
        
        return $this->denyAccess();
    }

    /**
     * Check current user authority
     *
     * @return boolean
     */
    protected function allowAccess()
    {
        // 获取所有的权限
        $routeNames = $this->authorities->routeNames();

        // 获取当前的 route name
        $routeName = Route::currentRouteName();
        
        if ($routeNames->contains($routeName)) {
            
            if (!auth()->user()) {
                return false;
            }

            // 获取当前用户的所有权限
            $user = auth()->user()->load('roles.authorities:id,route_name');

            $roles = $user->roles->isEmpty() ?
                null
                : $user->roles;

            if (!$roles->pluck('authorities')->collapse()->pluck('route_name')->contains($routeName)) {
               return false;
            }
        }

        return true;
    }

    /**
     * Get deny response
     *
     * @return void
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function denyAccess()
    {
        return abort(401);
    }
}