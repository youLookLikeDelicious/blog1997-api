<?php

namespace App\Listeners;

use App\Model\Log as ModelLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{

    /**
     * Variable
     *
     * @var ModelLog
     */
    protected $logModel;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ModelLog $logModel)
    {
        $this->logModel = $logModel;
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
        $logRecord = $this->logModel->select(['id', 'created_at'])
            ->where('user_id', $event->user->id)
            ->where('operate', 'login')
            ->orderBy('id', 'desc')
            ->first();

        if ($logRecord && $logRecord->created_at < time() - 12 * 60 * 60) {
            Log::info('登陆成功', ['operate' => 'login', 'user_id' => $event->user->id, 'result'=> 'success', ]);
        }
    }
}
