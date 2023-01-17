<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // after user registered, dispatch event
        if ($user->email) {
            $user->sendEmailVerificationNotification();
        }

        Log::info('用户注册', ['operate' => 'register', 'result'=> 'success']);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if ($user->email && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        $avatar = $user->getOriginal('avatar');

        if ( $avatar && $user->isDirty('avatar')) {
            $this->removeAvatar($avatar);
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        if ($avatar = $user->avatar) {
            $this->removeAvatar($avatar);
        }
    }

    /**
     * Remove avatar from storage
     *
     * @param String $avatar
     * @return void
     */
    protected function removeAvatar(String $avatar)
    {
        Storage::delete($avatar);
        
        if ($avatarExt = strrchr($avatar, '.')) {
            Storage::delete(str_replace($avatarExt, '.webp', $avatar));
        }
    }
}
