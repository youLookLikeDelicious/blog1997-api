<?php

namespace Tests\Unit\Notification;

use App\Model\User;
use App\Notifications\PasswordResetNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class PasswordRestTest extends TestCase
{
    /**
     * A basic unit test example.
     * @group unit
     * 
     * @return void
     */
    public function test()
    {
        $this->makeUser();

        Notification::fake();

        $this->user->sendPasswordResetNotification('token');

        Notification::assertSentTo($this->user, PasswordResetNotification::class);
    }
}
