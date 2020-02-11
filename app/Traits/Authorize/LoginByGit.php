<?php

namespace App\Traits\Authorize;

use App\Model\User;
use Illuminate\Http\Request;

trait LoginByGit {
     /**
     * 使用git账号登陆
     * @param Request $request
     * @return mixed
     */
    public function auth (Request $request) {
        // 获取code
        $code = $request->input('code');

        // 无效的code
        if (!$code) {
            return response()->error('code not fond');
        }

        // 在前台/后台登陆
        $p = $request->input('p', 'admin');
        if ($p === 'home') {
            $clientId = env('GIT_HOME_CLIENT_ID');
            $secret = env('GIT_HOME_CLIENT_SECRET');
        } else {
            $clientId = env('GIT_ADMIN_CLIENT_ID');
            $secret = env('GIT_ADMIN_CLIENT_SECRET');
        }

        // 获取访问令牌
        $url = 'https://github.com/login/oauth/access_token?';
        $url .= 'code='.$code;
        $url .= '&client_id='.$clientId;
        $url .= '&client_secret='.$secret;

        $result = $this->curl($url, [CURLOPT_POST => true]);
        // 获取git的access token
        preg_match('/access_token=(\w+)&/', $result, $match);

        if (!$match) {
            return response()->error('code已经失效，请重新登陆');
        }

        // 使用token访问获取用户信息
        $url = 'https://api.github.com/user';
        $result = $this->curl($url, [
            CURLOPT_HTTPHEADER => [
                'Authorization: token '.$match[1],
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36'
            ],
        ]);

        $result = trim($result, '"');
        $result = str_replace("\n", '', $result);
        $result = json_decode($result);

        // 如果用户信息没有保存，将用户信息入库
        $user = User::select(['id', 'name', 'avatar', 'article_sum'])
            ->where('type', 2)
            ->where('foreign_id', $result->id)
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $result->login,
                'avatar' => $result->avatar_url, // git头像
                'type' => 2,    // 表示使用github账号登陆
                'foreign_id' => $result->id // git账号的id
            ]);

            if (!$user) {
                return response()->error('系统错误，请联系管理员');
            }
        }

        // 将用户信息保存到session里面
        session(['user' => serialize($user)]);
        session()->save();

        // 保存用户数据
        return response()->success($user);
    }
}