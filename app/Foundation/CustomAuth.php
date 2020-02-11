<?php

namespace App\Foundation;

class CustomAuth {
    /**
     * 获取当前用户
     * @return mixed
     */
    public function user () {

        $user = session('user');

        // 已经登陆
        if ($user) {

            $user = unserialize($user);
            return $user;
        } else {
            // 没有登陆
            return false;
        }
    }

    /**
     * 获取当前用户的id
     * @return integer
     */
    public function id () {

        $user = $this->user();

        return $user ? $user->id : 0;
    }

    /**
     * 判断是否有用户登录
     */
    public function hasUser () {
        return session('user');
    }
}
