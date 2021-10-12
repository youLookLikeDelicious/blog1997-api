<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;

class Authenticate
{
    /**
     * Application instance
     *
     * @param Type $var
     */
    protected $app;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->sendFailedResponse();
        }

        $user->load('socialAccounts');

        if (!$this->runningUnitTests() && !$user->hasVerifiedEmail() && $user->socialAccounts->isEmpty()) {
            abort(403, __('auth.Your email has not verified yet.'));
        }

        return $next($request);
    }

    /**
     * Determine if the application is running unit tests.
     *
     * @return bool
     */
    protected function runningUnitTests()
    {
        return $this->app->runningInConsole() && $this->app->runningUnitTests();
    }

    /**
     * Send failed response when user not login
     *
     * @return mixed
     */
    public function sendFailedResponse()
    {
        return request()->expectsJson() ?
            response()->error(__('Not login'), 401)
            : redirect('/admin/login');
    }
}
