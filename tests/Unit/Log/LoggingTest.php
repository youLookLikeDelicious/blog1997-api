<?php

namespace Tests\Unit\Log;

use App\Models\Log as ModelLog;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use App\Events\LogEvent;

class LoggingTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * 测试日志记录
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        Event::fake();

        Log::info('log test for login', [ 'result' => 'success', 'operate' => 'login' ]);
        
        Event::assertDispatched(LogEvent::class, function ($event) {
            return $event->data['result'] === 'success';
        });

        Log::info('log test for login');
        Event::assertDispatched(LogEvent::class, 2);
    }
}
