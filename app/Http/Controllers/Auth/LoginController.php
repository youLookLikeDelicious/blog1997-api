<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Contract\Auth\Factory;
use App\Http\Controllers\Controller;
use App\Facades\Service\RSAService;
use App\Http\Requests\LoginByProviderRequest;
use App\Http\Resources\User as ResourcesUser;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('email', $request->email)->first();
 
        if ($user && Hash::check($request->password, $user->password)) {
            return $this->sendLoginResponse($request, $user);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
    
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
    protected function sendLoginResponse(Request $request, $user)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $user)
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
        return response()->success(new ResourcesUser($user, $user->createToken($request->device_name ?: 'web')->plainTextToken));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->where('name', $request->device_name ?: 'web')->delete();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
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
            'captcha'  => $request->input('captcha'),
            'key'      => $request->input('key')
        ]);
        
        $rules = [
            'email'    => 'required|string|email',
            'password' => 'required|string',
            'captcha'  => 'required|captcha_api:'.$request->input('key').',math'
        ];

        $request->validate($rules);
    }
}
