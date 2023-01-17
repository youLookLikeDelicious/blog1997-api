<?php

namespace Tests\Unit\Listener;

use App\Events\UserRegisterByProviderEvent;
use App\Listeners\RegisterUserListener;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\DatabaseRule;

class UserRegisterByProviderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test for listener
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $user = User::factory()->create([
            'avatar' => 'http://tiebapic.baidu.com/forum/pic/item/9822720e0cf3d7ca543cf630e51fbe096a63a9aa.jpg'
        ]);

        $event = new UserRegisterByProviderEvent($user);

        $listener = new RegisterUserListener();

        $listener->handle($event);
        $avatarPath = strstr($user->avatar, 'image');
        $this->assertFileExists(storage_path($avatarPath));
    }
}
