<?php
namespace App\Http\Controllers\Auth\Provider;

use App\Service\CurlService;
use App\Http\Resources\User as UserResource;
use App\Http\Controllers\Auth\Exception\ConnectionException;

class WechatProvider extends ProviderAbstract implements ProviderInterface
{
    /**
     * 获取请求第三方access TOKEN的地址
     *
     * @return string
     */
    protected function getRequestTokenUrl ()
    {
        // 通过code获取access token
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
        $url .= 'appid=' . $this->config['APP_ID'];
        $url .= '&secret=' . $this->config['SECRET'];
        $url .= '&code=' . $this->request->code;
        $url .= '&grant_type=authorization_code';

        return $url;
    }

    /**
     * 获取第三方的授权令牌
     *
     * @return void
     */
    protected function getToken()
    {
        $url = $this->getRequestTokenUrl();

        $result = CurlService::make($url, [CURLOPT_CONNECTTIMEOUT => 12]);

        // 将结果解析成数组
        $result = json_decode($result, true);

        if (!empty($result['errcode'])) {
            throw new ConnectionException($result['errmsg']);
        }

        return $result;
    }

    /**
     * Via token get user info
     *
     * @param string $token
     * @param string $openId
     * @return array|throw ConnectionException
     */
    protected function getUserByToken ($token, $openId = '')
    {
        // 通过access_token获取用户信息
        $url = 'https://api.weixin.qq.com/sns/userinfo?';
        $url .= 'access_token=' . $token;
        $url .= '&openid=' . $openId;
        
        $result = CurlService::make($url, [CURLOPT_CONNECTTIMEOUT => 12]);

        // 将结果解析成数组
        $result = json_decode($result, true);

        if (!empty($result['errcode'])) {
            throw new ConnectionException($result['errmsg']);
        }

        // 重新阻止第三放平台的用户信息
        // 微信说普通用户性别是 1和2，不普通的用户就不知了
        $user = [
            'name' => $result['nickname'],
            'gender' => ['1' => 'boy', '2' => 'girl'][$result['sex']] ?? 'keep_secret',
            'type' => 1,
            'avatar' => $result['headimgurl'],
            'foreign_id' => $result['unionid']
        ];

        return $user;
    }

    /**
     * Get vendor user info
     *
     * @return array
     */
    public function getVendorUserInfo()
    {
        $token = $this->getToken();

        $user = $this->getUserByToken($token['access_token'], $token['openid']);

        return $user;
    }
}