<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Auth;
use App\Models\RoleAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for creating auth
     * @group feature
     *
     * @return void
     */
    public function test_create_auth()
    {
        $this->makeUser('master');

        // 没有提交任何参数，会验证失败
        $response = $this->json('post', '/api/admin/auth');
        $response->assertStatus(400);

        // 提交正确的参数
        $response = $this->json('post', '/api/admin/auth', [
            'name' => '文章管理',
            'parent_id' => 0,
            'route_name' => 'article.index',
            'method' => 'post'
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'message' => '权限添加成功'
            ]);
    }

    /**
     * Test for creating auth
     * @group feature
     *
     * @return void
     */
    public function test_update_auth()
    {
        $this->makeUser('master');

        $auth = factory(Auth::class)->create();

        // 修改auth
        $response = $this->json('PUT', '/api/admin/auth/' . $auth->id, [
            'name' => 'updated auth',
            'url' => '',
            'method' => 'delete',
            'parent_id' => 0,
            'route_name' => 'article.delete'
        ]);

        $response->assertStatus(200);

        $this->assertEquals('updated auth', Auth::find($auth->id)->name);
    }

    /**
     * Test for delete auth
     * @group feature
     *
     * @return void
     */
    public function test_delete_auth()
    {
        $this->makeUser('master');

        $auth = factory(Auth::class)->create();

        RoleAuth::insert([
            ['auth_id' => $auth->id, 'role_id' => 12],
            ['auth_id' => $auth->id, 'role_id' => 12],
            ['auth_id' => $auth->id, 'role_id' => 12],
            ['auth_id' => $auth->id, 'role_id' => 12]
        ]);

        // 修改auth
        $response = $this->json('delete', '/api/admin/auth/' . $auth->id);

        $response->assertStatus(200);

        $this->assertNull(Auth::find($auth->id));
        // 获取 角色-权限 中间表的数据 
        $middleTable = RoleAuth::all();
        $this->assertTrue($middleTable->isEmpty());
    }

    /**
     * test getting index
     * @group feature
     *
     * @return void
     */
    public function test_index()
    {
        $this->makeUser('master');

        $auth = factory(Auth::class)->create();

        factory(Auth::class, 2)->create([
            'parent_id' => $auth->id
        ]);

        $response = $this->json('get', '/api/admin/auth');

        $response->assertStatus(200);
    }
}
