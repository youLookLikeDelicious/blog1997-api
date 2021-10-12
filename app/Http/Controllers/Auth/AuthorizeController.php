<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthorizeController extends Controller
{
    /**
     * 获取当前登陆的用户
     *
     * @return \Illuminate\Http\Response
     */
    public function currentUser ()
    {
        $user = Auth::user();
        
        if (Auth::user()) {
            return response()->success(new User($user) ?? []);
        }

        return response()->success('');
    }

    /**
     * 获取令牌
     *
     * @return \Illuminate\Http\Response
     */
    public function getCsrfToken()
    {
        return response()->success(session()->token());
    }
}
