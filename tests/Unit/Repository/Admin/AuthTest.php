<?php

namespace Tests\Unit\Repository\Admin;

use App\Repository\Admin\Auth as AdminAuth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Models\Auth as Model;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        // 实例话 repository
        $repository = app()->make(AdminAuth::class);

        // 添加 auth 记录
        $topAuth = factory(Model::class)->create(['name' => 'top auth']);
        factory(Model::class)->create(['name' => 'auth-1', 'parent_id' => $topAuth->id]);
        factory(Model::class)->create(['name' => 'auth-2', 'parent_id' => $topAuth->id]);
        factory(Model::class)->create(['name' => 'blog1997 auth', 'parent_id' => $topAuth->id]);

        // 测试 flatted
        $result = $repository->flatted();
        $this->assertEquals('top auth', $result[0]['name']);
        $this->assertEquals(4, count($result));

        // 测试 条件查询
        $request = new Request(['name' => 'blog1997', ['parent_id' => $topAuth->id]]);
        $result = $repository->all($request);
        $this->assertEquals(1, $result->count());
    }
}
