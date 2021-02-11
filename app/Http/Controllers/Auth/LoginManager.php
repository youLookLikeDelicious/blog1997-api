<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Support\Manager;
use App\Contract\Auth\Factory;
use App\Contract\Repository\User as UserContract;
use App\Http\Controllers\Auth\Provider\GithubProvider;
use App\Http\Controllers\Auth\Provider\WechatProvider;
use App\Repository\SocialAccount;

class LoginManager extends Manager implements Factory
{
    /**
     * Create an instance of the specified driver.
     *
     * @return App\Http\Controllers\Auth\Provider\ProviderAbstract
     */
    protected function createGithubDriver()
    {
        $config = [
            'CLIENT_ID' => config('app.git_client_id'),
            'SECRET' => config('app.git_secret')
        ];

        return $this->buildProvider(
            GithubProvider::class, $config
        );
    }
    
    /**
     * Create an instance of the specified driver.
     *
     * @return App\Http\Controllers\Auth\Provider\ProviderAbstract
     */
    protected function createWechatDriver()
    {
        $config = [
            'APP_ID' => config('app.wechat_app_id'),
            'SECRET' => config('app.wechat_secret')
        ];

        return $this->buildProvider(
            WechatProvider::class, $config
        );
    }

    /**
     * build provider instance
     *
     * @return Provider/ProviderAbstract
     */
    protected function buildProvider ($provider, $config)
    {
        return new $provider(
            $this->app->make(UserContract::class),
            $this->app->make(SocialAccount::class),
            $this->app->make('request'),
            $config
        );
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'github';
    }
}