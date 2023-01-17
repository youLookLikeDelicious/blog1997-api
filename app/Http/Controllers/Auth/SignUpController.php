<?php
namespace App\Http\Controllers\Auth;

use App\Contract\Repository\User as RepositoryUser;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SignUpController
{
    use VerifiesEmails;

    /**
     * User repository
     *
     * @var RepositoryUser
     */
    protected $user;

    public function __construct(RepositoryUser $user)
    {
        $this->user = $user;
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $data = $request->only(['email', 'password']);

        User::updateOrCreate(
            ['email' => $data['email'], 'password' => Hash::make($data['password'])],
            ['name' => substr($data['email'], 0, 3) . '***' . strrchr($data['email'], '@')]
        );

        $message = allowSendEmail() ? '连接已经发送到邮箱,请注意查收' : '注册成功';

        return response()->success('', $message);
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(401, __('message.Invalid expired'));
        }

        $this->validateVerifyRequest($request);

        $user = $this->user->findByEmail($request['email']);

        if (!$user || $user->id != $request['id']) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $this->redirectTo();
    }

    /**
     * Validate request witch to sign up a new user
     *
     * @param Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'email' => [
                'required', 'email',
                Rule::unique('user')->whereNotNull('email_verified_at')
            ],
            'password' => 'required|confirmed|max:27|min:9',
            'captcha' => 'required|captcha'
        ]);
    }

    /**
     * Validate request witch to verify email
     *
     * @param Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateVerifyRequest(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'email' => 'required|email'
        ]);
    }

    /**
     * Redirect response to sign up page
     *
     * @return redirect
     */
    public function redirectTo()
    {
        return redirect('/');
    }
}