<?php

namespace App\Http\Controllers\Auth\Provider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\User as UserModel;
use Illuminate\Support\Facades\Auth;
use App\Contract\Repository\User as UserContract;
use App\Model\SocialAccount;
use App\Repository\SocialAccount as RepositorySocialAccount;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

abstract class ProviderAbstract
{
    use ThrottlesLogins;
    
    /**
     * Request
     *
     * @var Request
     */
    protected $request;

    /**
     * User repository
     * @var App\Contract\Repository\User
     */
    protected $userRepository;

    /**
     * Social account repository
     *
     * @var RepositorySocialAccount
     */
    protected $socialAccountRepository;

    /**
     * 第三方平台的Id和密钥
     *
     * @var array
     */
    protected $config;

    /**
     * 从第三方平台获取的用户信息
     *
     * @var array
     */
    protected $vendorUserInfo;

    /**
     * Create Provider Instance
     *
     * @param UserContract $userRepository
     * @param RepositorySocialAccount $socialAccountRepository
     * @param Request $request
     * @param array $config
     */
    public function __construct(UserContract $userRepository, RepositorySocialAccount $socialAccountRepository, Request $request, array $config)
    {
        $this->validateLogin($request);

        $this->request = $request;

        $this->userRepository = $userRepository;
        $this->socialAccountRepository = $socialAccountRepository;
        $this->config = $config;
    }


    /**
     * Validate login request
     *
     * @param Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'redirect' => 'nullable|in:home,admin'
        ]);
    }

    /**
     * Login by social account
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        $user = $this->retrieveUser();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($this->request)) {
            $this->fireLockoutEvent($this->request);

            return $this->sendLockoutResponse($this->request);
        }

        $this->clearLoginAttempts($this->request);

        return $this->sendLoginResponse($user);
    }

    /**
     * 判断是否请求的管理员页面
     *
     * @return boolean
     */
    protected function isRequestToAdmin()
    {
        return $this->request->redirect && $this->request->redirect === 'admin';
    }

    /**
     * Undocumented function
     *
     * @param UserModel $user
     * @return void
     */
    public function sendLoginResponse(UserModel $user)
    {
        $this->request->session()->regenerate();
        
        $this->clearLoginAttempts($this->request);
        
        $this->authorizeUser($user);
        
        // 登陆前台界面
        if (! $this->isRequestToAdmin()) {
            return response()->success($user);
        }

        // 登陆管理员界面
        return response()->success(new UserResource($user), '用户登陆成功');
    }

    /**
     * Send error response
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse()
    {
        $this->incrementLoginAttempts($this->request);
        return response()->error('Access deny', 401);
    }

    /**
     * 尝试获取用户信息，如果不存在，创建之
     *
     * @param array $vendorInfo
     * @return \App\Model\User|null
     */
    public function retrieveUser()
    {
        $user = $this->user();

        if ($user) {
            $user->load('user:id,name,avatar,email,created_at,remember_token,password');
            return $user->user;
        }

        // 通过第三方登陆，直接创建一个新的账号

        if (! $this->isRequestToAdmin()) {
            $user = $this->createNewAccount();
        }

        return $user;
    }

    /**
     * Create new account via vendor information
     *
     * @return UserModel
     */
    protected function createNewAccount()
    {
        $user = '';

        $vendorInfo = $this->vendorUserInfo;

        DB::transaction(function () use ($vendorInfo, &$user) {
            // 用户不存在
            // 创建第三方平台的信息
            $socialAccount = $this->createSocialAccount($vendorInfo['type'], $vendorInfo['foreign_id']);

            //创建用户
            $user = UserModel::create([
                'name' => $vendorInfo['name'],
                'avatar' => $vendorInfo['avatar'],
                'email' => $socialAccount->foreign_id . '@' . $socialAccount->type . '.com'
            ]);

            $socialAccount->user()->associate($user);

            $socialAccount->save();
        });

        return $user;
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($this->vendorUserInfo['name']).'|'.$request->ip();
    }

    /**
     * 创建第三方账号记录
     *
     * @param int $type
     * @param string $foreign_id
     * @param int $userId
     * 
     * @return SocialAccount
     */
    protected function createSocialAccount($type, $foreign_id, $userId = '')
    {
        $data = [
            'type' => $type,
            'foreign_id' => $foreign_id,
        ];

        if ($userId) {
            $data['user_id'] = $userId;
        }

        return SocialAccount::create($data);
    }

    /**
     * 授权用户
     *
     * @param \App\Model\User $user
     * @return $user
     */
    protected function authorizeUser(UserModel $user)
    {
        Auth::login($user, true);
        return $this;
    }

    /**
     * Bind social account
     *
     * @return \Illuminate\Http\Response
     */
    public function bind()
    {
        // 获取第三方平台的用户信息
        $user = $this->getVendorUserInfo();

        $socialAccount = $this->socialAccountRepository
            ->find($user['foreign_id'], $user['type']);

        // 第三方账号已存在
        if ($socialAccount) {
            $socialAccount->load('user:id,name');

            $result = [
                'conflictAccount' => $socialAccount->user,
                'socialAccount' => $user,
            ];

            return response()->error('该账号已被绑定', 400, $result);
        }

        $socialAccount = $this->createSocialAccount($user['type'], $user['foreign_id'], auth()->id());

        return response()->success($socialAccount, '账号绑定成功');
    }

    /**
     * Rebind social account
     *
     * @return \Illuminate\Http\Response
     */
    public function rebind()
    {
        $socialAccount = $this->user();

        if (! $socialAccount) {
           return response()->error('账号不存在，无需重新绑定');
        }

        $socialAccount->user_id = auth()->id();
        $socialAccount->save();

        return response()->success($socialAccount, '账号绑定成功');
    }

    /**
     * Cancel bind social account
     *
     * @return \Illuminate\Http\Response
     */
    public function unbind()
    {
        // 获取第三方平台的用户信息
        $socialAccount = $this->user();

        $socialAccount->delete();

        return response()->success('', '解绑成功');
    }

    /**
     * Get local social count record
     *
     * @return SocialAccount|null
     */
    public function user()
    {
        $user = $this->getVendorUserInfo();

        $this->vendorUserInfo = $user;

        $socialAccount = $this->socialAccountRepository
            ->find($user['foreign_id'], $user['type']);
        
        return $socialAccount;
    }

    /**
     * Get name field to throttle
     *
     * @return string
     */
    public function username()
    {
        return $this->vendorUserInfo['name'] ?? '该账号';
    }

    /**
     * 获取第三方的授权令牌
     *
     * @return string
     */
    abstract protected function getToken();

    /**
     * 使用令牌获取第三方的用户信息
     *
     * @param string $token
     * @return json
     */
    abstract protected function getUserByToken($token, $openId = '');

    /**
     * 获取 第三方access TOKEN的地址
     *
     * @return string
     */
    abstract protected function getRequestTokenUrl();

    /**
     * Get user vendor information
     *
     * @return array
     */
    abstract protected function getVendorUserInfo();
}
