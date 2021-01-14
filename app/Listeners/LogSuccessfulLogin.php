<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
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
     * Registered by framework
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        Log::info('登陆成功', ['operate' => 'login', 'user_id' => $event->user->id, 'result'=> 'success', ]);
    }
}
