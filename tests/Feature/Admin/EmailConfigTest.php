<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class EmailConfigTest extends TestCase
{
    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_config_email()
    {
        $this->makeUser();

        // 获取邮箱配置
        $response = $this->get('/api/admin/email-config');

        $response->assertStatus(200);

        // 添加相关配置
        $response = $this->post('/api/admin/email-config', ['password' => '']);
        $response->assertStatus(400)
            ->assertJsonStructure([
                'message' =>  [
                    'driver', 'email_server', 'password',  'port', 'email_addr', 'encryption', 'sender'
                ]
            ]);


        // 添加相关配置
        $response = $this->post('/api/admin/email-config', [
            'driver' => 'smtp',
            'email_server' => '163.com',
            'port' => '443',
            'email_addr' => 'blog1997@163.com',
            'encryption' => 'ssl',
            'sender' => 'Blog1997',
            'password' => 'pwdpwdpwd'
        ]);
        $response->assertStatus(200);
        
        $emailConfig = json_decode($response->getContent())->data;

        // 修改相关配置
        $response = $this->put('/api/admin/email-config/' . $emailConfig->id, [
            'sender' => 'chaos',
            'driver' => 'smtp',
            'email_server' => '163.com',
            'port' => '443',
            'email_addr' => 'blog1997@163.com',
            'encryption' => 'ssl',
            'sender' => 'Blog1997',
            'password' => 'pwdpwdpwd'
        ]);

        $response->assertStatus(200);

        $this->assertNull(Cache::get('email-config'));

        // 获取邮箱配置
        $response = $this->get('/api/admin/email-config');
        $response->assertStatus(200);
        $this->assertEquals($emailConfig->id, Cache::get('email-config')->id);
    }
}
