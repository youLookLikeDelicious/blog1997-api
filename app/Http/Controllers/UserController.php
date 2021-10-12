<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Facades\Upload;
use App\Model\SocialAccount;
use Illuminate\Http\Request;
use App\Contract\Auth\Factory;
use App\Http\Controllers\Controller;
use App\Repository\User as Repository;
use App\Http\Requests\UploadImageRequest;
use App\Http\Requests\LoginByProviderRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Resources\User as ResourcesUser;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * @group User management
 * 
 * 用户管理
 */
class UserController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Get user List
     * 
     * 获取用户列表
     *
     * @param Request $request
     * @param Repository $repository
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Repository $repository)
    {
        return $repository->index($request);
    }

    /**
     * Reset user avatar
     *
     * @param UploadImageRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if (isset($data['avatar'])) {
            $avatarUrl = Upload::uploadImage($data['avatar'], 'avatar', 300, 300, false)
                ->getFileList()[0];
            $data['avatar'] = $avatarUrl;
        }

        $user->update($data);

        return response()->success($user->makeHidden(['password', 'remember_token']), '修改成功');
    }

    /**
     * Bind social account to exists account
     *
     * @param LoginByProviderRequest $request
     * @param Factory $factory
     * @return \Illuminate\Http\Response
     */
    public function bind(LoginByProviderRequest $request, Factory $factory)
    {
        $data = $request->validated();

        return $factory->driver($data['type'] ?? '')->bind();
    }

    /**
     * Bind social account to exists account
     *
     * @param LoginByProviderRequest $request
     * @param Factory $factory
     * @return \Illuminate\Http\Response
     */
    public function rebind(LoginByProviderRequest $request, Factory $factory)
    {
        $data = $request->validated();

        return $factory->driver($data['type'] ?? '')->rebind();
    }

    /**
     * Bind social account to exists account
     *
     * @param LoginByProviderRequest $request
     * @param Factory $factory
     * @return \Illuminate\Http\Response
     */
    public function unbind(SocialAccount $account)
    {
        if ($account->user_id !== auth()->id()) {
            return response()->error('授权失败');
        }
        
        $account->delete();

        return response()->success('', '解除绑定成功');
    }

    /**
     * Get user extra information
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = auth()->user();

        $user->load('socialAccounts:id,type,created_at,user_id');

        return response()->success($user->socialAccounts);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->success('', '邮件已发送，请注意查收~');
    }

    /**
     * destroy
     * 
     * 注销账号
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->update([
            'deleted_at' => time()
        ]);

        return response()->success('', '管理员删除成功');
    }

    /**
     * Freeze the account
     * 冻结账号
     *
     * @return \Illuminate\Http\Response
     */
    public function freeze(User $user)
    {
        $user->freeze_at = time();

        $user->save();

        return response()->success('', '冻结成功');
    }

    /**
     * Unfreeze the account
     * 
     * 冻结账号
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function Unfreeze(User $user)
    {
        $user->freeze_at = 0;

        $user->save();

        return response()->success('', '解冻成功');
    }

    /**
     * Get user info
     * 
     * 获取用户详情
     *
     * @param User $user
     * @return @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->success(new ResourcesUser($user));
    }
}
