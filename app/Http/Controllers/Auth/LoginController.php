<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\User;
use Illuminate\Http\Request;
use App\Contract\Auth\Factory;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Facades\Service\RSAService;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\LoginByProviderRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * @group User Login
 * Login user
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 5;
    protected $decayMinutes = 10;
    /**
     * 通过第三方账号登陆
     *
     * @param LoginByProviderRequest $request
     * @param Factory $factory
     * @return response
     */
    public function loginByProvider(LoginByProviderRequest $request, Factory $factory)
    {
        $data = $request->validated();
        
        return $factory->driver($data['type'] ?? '')->login();
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        // set remember me expire time
        $rememberTokenExpireMinutes = 120;

        // first we need to get the "remember me" cookie's key, this key is generate by laravel randomly
        // it looks like: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d
        $rememberTokenName = Auth::getRecallerName();

        // reset that cookie's expire time
        Cookie::queue($rememberTokenName, Cookie::get($rememberTokenName), $rememberTokenExpireMinutes);

        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return response()->success(new User($user));
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return response()->success('登出成功');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->request->add(['remember' => true]);

        $request->replace([
            'email'    => RSAService::decrypt($request->input('email')),
            'password' => RSAService::decrypt($request->input('password')),
            'captcha'  => $request->input('captcha')
        ]);
        
        $rules = [
            'email'    => 'required|string|email',
            'password' => 'required|string',
            'captcha'  => 'required|captcha'
        ];

        $request->validate($rules);
    }
}
