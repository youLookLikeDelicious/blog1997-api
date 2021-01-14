<?php

namespace Tests\Unit\Listener;

use App\Events\LogEvent;
use App\Listeners\LogListener;
use App\Model\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogListenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 测试日志事件
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $event = new LogEvent([
            'message' => 'test',
            'level' => 'debug',
            'result' => 'neutral',
            'operate' => 'log'
        ]);

        $listener = app()->make(LogListener::class);

        $listener->handle($event);

        $log = Log::all();
        $this->assertNotNull($log);
    }
}
