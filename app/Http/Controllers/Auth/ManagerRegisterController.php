<?php

namespace App\Http\Controllers\Auth;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ManagerRequest;

class ManagerRegisterController extends Controller
{
    /**
     * Show create Page
     *
     * @param Request $request
     * @return view
     */
    public function create(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, '该链接已经失效或验证码无效!详情请咨询管理员');
        }

        // 获取用户信息
        $manager = User::select('email', 'id')
            ->findOrFail($request->id);

        $url = URL::signedRoute(
            'manager.inti.password',
            ['manager' => $manager]
        );

        return view('auth.auth', [
            'title' => '注册',
            'manager' => $manager,
            'url' => $url
        ]);
    }

    /**
     * Update Manager password
     *
     * @param ManagerRequest $request
     * @return response
     */
    public function update(ManagerRequest $request, User $manager)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = time();
        $manager->update($data);

        return response()->success('');
    }
}
