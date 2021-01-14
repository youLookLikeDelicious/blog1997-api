<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserRegisterByProviderEvent;

class RegisterUserListener implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegisterByProviderEvent $event)
    {
        $user = $event->user;

        $user->avatar = $this->uploadAvatar($user->avatar);

        $user->save();
    }
    

    /**
     * 将其他平台的头像，上传到本地服务
     *
     * @param string $avatar
     * @return string
     */
    public function uploadAvatar ($avatar)
    {
        if (! $avatar) {
            return '';
        }

        // 如果用户有头像，将头像保存在本站

        // 生成头像保存的位置
        $avatarBaseUrl = "/image/avatar/" . date('Y-m-d') . '/';
        $fileName = md5(Str::random(21) . uniqid());

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $fullName = $avatarBaseUrl . $fileName;
        // 如果头像不存在，创建之
        Storage::put($fullName, file_get_contents($avatar, false, stream_context_create($arrContextOptions)));

        // 返回
        return $fullName;
    }
}
