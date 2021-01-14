<?php
namespace App\Http\Controllers\Auth\Provider;

use App\Http\Controllers\Auth\Exception\ConnectionException;
use App\Service\CurlService;

class GithubProvider extends ProviderAbstract implements ProviderInterface
{
    protected $maxAttempts = 5;

    protected $decayMinutes = 30;

    /**
     * 获取 第三方access TOKEN的地址
     *
     * @return string
     */
    protected function getRequestTokenUrl ()
    {
        // 获取访问令牌
        $url = 'https://github.com/login/oauth/access_token?';
        $url .= 'code=' . $this->request->code;
        $url .= '&client_id=' . $this->config['CLIENT_ID'];
        $url .= '&client_secret=' . $this->config['SECRET'];

        return $url;
    }

    /**
     * 获取第三方的授权令牌
     *
     * @return string
     */
    protected function getToken ()
    {
        $url = $this->getRequestTokenUrl();

        $result = CurlService::make($url, [CURLOPT_POST => true, CURLOPT_CONNECTTIMEOUT => 12]);

        // 获取git的access token
        preg_match('/access_token=(\w+)&/', $result, $match);

        // 访问令牌获取失败
        if (!$match) {
            throw new ConnectionException('Sorry, We cannot connect to git server for that moment');
        }

        return $match[1];
    }

    /**
     * 使用令牌获取第三方的用户信息
     *
     * @param string $token
     * @return json
     */
    protected function getUserByToken ($token, $openId = '')
    {
        // 使用token访问获取用户信息
        $url = 'https://api.github.com/user';

        $result = CurlService::make($url, [
            CURLOPT_HTTPHEADER => [
                'Authorization: token '. $token,
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36'
            ],
            CURLOPT_CONNECTTIMEOUT => 23
        ]);

        $result = trim($result, '"');
        $result = str_replace("\n", '', $result);
        $result = json_decode($result);

        if (!$result) {
            throw new ConnectionException('We cannot retrieve user info from github');
        }

        $user = [
            'type' => 2,                     // 表示使用github账号登陆
            'name' => $result->login,
            'foreign_id' => $result->id,     // git账号的id
            'avatar' => $result->avatar_url  // git头像
        ];

        return $user;
    }

    /**
     * Get user vendor information
     *
     * @return array
     */
    protected function getVendorUserInfo()
    {
        $token = $this->getToken();

        $user = $this->getUserByToken($token);

        return $user;
    }
} 