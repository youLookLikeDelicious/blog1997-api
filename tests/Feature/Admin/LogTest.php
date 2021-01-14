<?php

namespace Tests\Feature\Admin;

use App\Model\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_get_log()
    {
        $this->makeUser();

        // 测试没有数据的情况
        $response = $this->get('/api/admin/log/');
        $response->assertStatus(200);

        // 添加用户操作日志
        factory(Log::class, 21)->create([
            'user_id' => $this->user->id
        ]);
        // 添加schedule日志
        factory(Log::class, 20)->create([
            'operate' => 'schedule',
            'user_id' => 0
        ]);

        // 获取日志
        $response = $this->get('/api/admin/log/');
        $response->assertStatus(200);

        // 获取指定用户的相关操作日志
        $response = $this->get('/api/admin/log?startDate=2020-01-14&endDate=2020-01-15&email=' . $this->user->email);
        $response->dump();
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'pagination' => [
                        'total' => 0
                    ]
                ]]);

        // 获取计划任务日志
        $this->get('/api/admin/log/schedule')->assertStatus(200);
    }
}
