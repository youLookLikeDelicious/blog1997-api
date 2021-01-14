<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthorizeController extends Controller
{
    /**
     * 获取当前登陆的用户
     *
     * @return void
     */
    public function currentUser ()
    {
        $user = Auth::user();
        
        if (Auth::user()) {
            return response()->success(new User($user) ?? []);
        }

        return response()->success('');
    }
}
