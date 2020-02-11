<?php

namespace App\Http\Controllers\Auth;

use App\Facades\CustomAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorize\LoginByGit;

class AuthorizeController extends Controller
{

    use LoginByGit;
    
    /**
     * 执行curl命令
     * @param $url
     * @param $opt
     * @return bool|string
     */
    protected function curl ($url, $opt) {
        $ch = curl_init($url);

        foreach ($opt as $k => $v) {
            curl_setopt($ch, $k, $v);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        return $result;
    }

    /**
     * @param Request $request
     * 获取当前的用户
     * @return mixed
     */
    public function curUser (Request $request) {

        // 已经登陆
        if (CustomAuth::hasUser()) {

            $user = CustomAuth::user();

            return response()->success($user);
        } else {
            // 没有登录
            return response()->success();
        }
    }

    /**
     * 登出操作
     * @param Request $request
     * @return mixed
     */
    public function logout (Request $request) {

        $request->session()->forget('user');

        return response()->success(null, '登出成功');
    }
}
