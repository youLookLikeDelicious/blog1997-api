<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\SystemSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemSettingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_system_setting()
    {
        $this->makeUser();

        $response = $this->get('/api/admin/system-setting');

        $response->assertStatus(200)
            ->assertJson([
                'data' => null
            ]);

        // 添加配置
        $setting = SystemSetting::create([
            'enable_comment' => 'yes',
            'verify_comment' => 'yes'
        ]);

        $response = $this->get('/api/admin/system-setting')
            ->assertJson([
                'data' => [
                    'enable_comment' => 'yes',
                    'verify_comment' => 'yes',
                ]
            ]);

        // 修改配置
        $response = $this->put('/api/admin/system-setting/' . $setting->id, [
            'enable_comment' => 'no',
            'verify_comment' => 'no'
        ]);

        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'enable_comment' => 'no',
                'verify_comment' => 'no',
            ]
        ]);
    }
}
