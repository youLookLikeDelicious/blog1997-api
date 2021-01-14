<?php

namespace Tests\Unit\Commands;

use App\Model\Role;
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
        factory(Role::class)->create([
            'name' => 'Master'
        ]);

        $this->artisan('master:create email=454948077@qq.com')
            ->expectsOutput('Email has been send, please checkout!');

        $this->artisan('master:create email=454948077@qq.com')
            ->expectsOutput('There is already one master!');
    }
}
