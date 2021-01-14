<?php

namespace App\Observers;

use App\Model\Auth;
use Illuminate\Support\Facades\DB;

class AuthObserver
{
    /**
     * Handle the auth "created" event.
     * 
     * 生成auth_path
     *
     * @param  \App\Model\Auth  $auth
     * @return void
     */
    public function created(Auth $auth)
    {
        $auth->auth_path = $this->getAuthPath($auth);
        $auth->save();
    }

    /**
     * Handle the auth "updated" event.
     *
     * @param  \App\Model\Auth  $auth
     * @return void
     */
    public function updating(Auth $auth)
    {
        if ($auth->isDirty('parent_id')) {
            $auth->auth_path = $this->getAuthPath($auth);

            $originAuthPath = $auth->getOriginal('auth_path');

            // 更新子权限的auth_path
            Auth::where('auth_path', 'like', $originAuthPath . '%')
                ->update([
                    'auth_path' => DB::raw("REPLACE(auth_path, '{$originAuthPath}', '{$auth->auth_path}')")
                ]);
        }
    }

    /**
     * Handle the auth "deleted" event.
     *
     * @param  \App\Model\Auth  $auth
     * @return void
     */
    public function deleted(Auth $auth)
    {
        $auth->roles()->detach();
    }

    /**
     * 生成auth path
     *
     * @param Auth $auth
     * @return String
     */
    protected function getAuthPath(Auth $auth)
    {
        if ($auth->parent_id) {
            $parentAuth = Auth::select('id', 'auth_path')
                ->findOrFail($auth->parent_id);

            $authPath = $parentAuth->auth_path . $auth->id . '_';
        } else {
            $authPath = $auth->id . '_';
        }

        return $authPath;
    }
}
