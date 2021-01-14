<?php

namespace App\Http\Middleware;

use App\Model\Role as ModelRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;

class Role
{
    public $app;
    /**
     * Create instance
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = '')
    {
        if (auth()->isMaster () || $this->app->runningUnitTests()) {
            return $this->sendNextRequest($request, $next);
        }

        $role = ModelRole::with('users')->where('name', $role)->first();

        if ($role && $role->users->contains(auth()->user())) {
            return $this->sendNextRequest($request, $next);
        }
        
        abort(401);
    }

    protected function sendNextRequest($request, $next)
    {
        $request->merge(['user_id' => Auth::id()]);
        
        return $next($request);
    }

    /**
     * 重定向到登陆界面
     *
     * @return view
     */
    public function redirectTo()
    {
        return redirect('admin/login');
    }
}
