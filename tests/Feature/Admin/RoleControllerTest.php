<?php

namespace Tests\Feature\Admin;

use App\Models\Auth;
use App\Models\Role;
use App\Models\RoleAuth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create Role
     * @group feature
     *
     * @return void
     */
    public function test_create()
    {
        $this->makeUser('master');

        factory(Auth::class)->create([
            'id' => 9990
        ]);
        factory(Auth::class)->create([
            'id' => 9991
        ]);
        factory(Auth::class)->create([
            'id' => 9992
        ]);

        // 测试上传角色-----------------------------------------------------
        $response = $this->json('post', '/api/admin/role', [
            'name' => 'Author',
            'remark' => '备注',
            'authorities' => [9990, 9991, 9992]
        ]);
        $response->assertStatus(200);

        $role = Role::orderBy('id', 'desc')->first();

        // 测试修改角色-----------------------------------------------------
        $response = $this->json('PUT', '/api/admin/role/' . $role->id, [
            'name' => 'Master - put',
            'remark' => '备注',
            'authorities' => [9990]
        ]);
        $roleAuth = RoleAuth::selectRaw('count(role_id) as count')->first();
        $this->assertEquals(1, $roleAuth->count);
        $response->assertStatus(200);

        // 测试删除角色-----------------------------------------------------
        $response = $this->json('delete', '/api/admin/role/' . $role->id);
        $response->assertStatus(200);
        $roleAuth = RoleAuth::selectRaw('count(role_id) as count')->first();
        $this->assertEquals(0, $roleAuth->count);
    }

    /**
     * Test create Role for failed case
     * @group feature
     *
     * @return void
     */
    public function test_create_without_parameters()
    {
        $this->makeUser('master');

        $response = $this->json('post', '/api/admin/role');

        $response->assertStatus(400)
            ->assertJson([
                'message' => [
                    'name' => ['名称 属性是必填的.']
                ]
            ]);
    }

    /**
     * Test get role
     * @group feature
     *
     * @return void
     */
    public function test_index()
    {
        $this->makeUser('master');

        Role::factory()->count(20)->create();

        $response = $this->json('get', '/api/admin/role');
        
        $response->assertStatus(200);
    }
}
