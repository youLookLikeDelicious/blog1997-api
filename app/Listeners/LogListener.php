<?php

namespace App\Listeners;

use App\Events\LogEvent;
use App\Models\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class LogListener implements ShouldQueue
{
    /**
     * Log Eloquent Model
     * ReCommend to log data to mongodb rather than relation ship database
     * 
     * @var Log
     */
    protected  $model;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Log $model)
    {
        $this->model = $model;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(LogEvent $event)
    {
        $this->model->create($event->data);
    }
}
