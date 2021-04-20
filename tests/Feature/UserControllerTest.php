<?php

namespace Tests\Feature;

use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test reset user avatar
     * @group feature
     *
     * @return void
     */
    public function test_reset_avatar()
    {
        $this->makeUser();

        Storage::fake('avatars');
        
        $response = $this->post('/api/user/update/' . $this->user->id, [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);
        
        $response->assertStatus(200);
        $avatar = json_decode($response->getContent())->data->avatar;

        $realUser = User::find($this->user->id);
        $this->assertEquals($avatar, $realUser->avatar);
    }

    /**
     * Test reset user name and email
     * @group feature
     *
     * @return void
     */
    public function test_reset_name_email()
    {
        $this->makeUser();
        
        $response = $this->post('/api/user/update/' . $this->user->id, [
            'name' => 'new name',
            'email' => '1231@qq.com'
        ]);
        
        $response->assertStatus(200);
        $user = json_decode($response->getContent())->data;

        $realUser = User::find($this->user->id);
        $this->assertEquals($user->name, $realUser->name);
        $this->assertEquals($user->email, $realUser->email);
    }

    /**
     * Test reset user without parameters
     * @group feature
     *
     * @return void
     */
    public function test_without_parameters()
    {
        $this->makeUser();
        
        $response = $this->post('/api/user/update/' . $this->user->id, []);

        $response->assertStatus(404);
    }

    /**
     * 测试登出
     * @group feature
     *
     * @return void
     */
    public function test_logout()
    {
        Event::fake();

        $this->makeUser();

        Auth::logout();

        Event::assertDispatched(\Illuminate\Auth\Events\Logout::class);
    }

    /**
     * 测试重置密码
     * @group feature
     *
     * @return void
     */
    public function test_reset_password()
    {
        $response = $this->json('post', 'api/user/password/reset', ['email' => 'blog1997123@qq.com']);
        // redirect
        $response->assertStatus(302);
    }

    /**
     * 测试注销账号
     * @group feature
     *
     * @return void
     */
    public function test_destroy_user()
    {
        $this->makeUser();

        $response = $this->json('delete', '/api/user/' . $this->user->id);

        $response->assertStatus(200);
        
        $this->assertNotEquals(User::find($this->user->id)->deleted_at, 0);
    }
}
