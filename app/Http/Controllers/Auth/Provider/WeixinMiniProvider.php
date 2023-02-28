<?php
namespace App\Http\Controllers\Auth\Provider;

use Facade\FlareClient\Http\Client;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WeixinMiniProvider extends ProviderAbstract implements ProviderInterface
{
    /**
     * 获取第三方的授权令牌
     *
     * @return string
     */
    protected function getToken()
    {

    }

    /**
     * 使用令牌获取第三方的用户信息
     *
     * @param string $token
     * @return json
     */
    protected function getUserByToken($token, $openId = '')
    {

    }

    /**
     * 获取 第三方access TOKEN的地址
     *
     * @return string
     */
    protected function getRequestTokenUrl()
    {

    }

    /**
     * Get user vendor information
     *
     * @return array
     */
    protected function getVendorUserInfo()
    {
        if (empty($this->config['APP_ID']) || empty($this->config['SECRET'])) {
            throw new RuntimeException('小程序参数错误');
        }

        $response = Http::get('https://api.weixin.qq.com/sns/jscode2session', [
            'appid'      => $this->config['APP_ID'],
            'secret'     => $this->config['SECRET'],
            'js_code'    => request('code'),
            'grant_type' => 'authorization_code'
        ]);
        
        $data = $response->json();

        return [
            'foreign_id' => $data['openid'],
            'type'       => 1,
            'gender'     => ['1' => 'boy', '2' => 'girl'][$this->request->gender] ?? 'keep_secret',
            'avatar'     => $this->request->avatarUrl,
            'name'       => $this->request->nickName
        ];
    }
}