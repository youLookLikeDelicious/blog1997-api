<?php

namespace Tests\Unit\Commands;

use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create master from console
     * @group unit
     *
     * @return void
     */
    public function test_console_command()
    {
        // 创建一个Master 角色
        Role::factory()->create([
            'name' => 'Master'
        ]);

        $this->artisan('master:create email=454948077@qq.com password=888888')
            ->expectsOutput('Email has been send, please checkout!');

        $this->artisan('master:create email=454948077@qq.com password=888888')
            ->expectsOutput('There is already a master!');
    }
}
