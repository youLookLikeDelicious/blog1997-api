<?php

namespace Tests\Feature\Admin;

use App\Model\Role;
use App\Model\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create manager
     * @group feature
     *
     * @return void
     */
    public function test_store_update_destroy()
    {
        $this->makeUser('master');

        $roles = factory(Role::class, 3)->create();

        // mock event
        Event::fake();

        $response = $this->json('put', '/api/admin/manager/' . $this->user->id, [
            'roles' => [$roles[0]->id]
        ]);

        $response->assertStatus(200);

        // 获取创建的管理员
        $manager = json_decode($response->getContent())->data;
        $managerRoles = User::with('roles')->find($manager->id)->roles;
        $this->assertEquals(1, $managerRoles->count());

        // ==================================================
        //  测试更新部分
        // ==================================================
        $response = $this->json('put', '/api/admin/manager/' . $manager->id, [
            'roles' => [$roles[1]->id, $roles[2]->id]
        ]);

        $managerRoles = User::with('roles')->find($manager->id)->roles;
        $this->assertEquals(2, $managerRoles->count());

        // ==================================================
        //  测试列表部分
        // ==================================================

        $response = $this->json('get', '/api/admin/manager');
        $response->assertStatus(200);
    }

    /**
     * Test get resource which is needed by create
     * @group feature
     *
     * @return void
     */
    public function test_create()
    {
        $this->makeUser('master');

        // 获取创建时需要的表单数据
        $response = $this->json('get', '/api/admin/manager/create');
        $response->assertStatus(200);

        // mock some resource
        $manager = factory(Role::class, 20)->create();

        $response = $this->json('get', '/api/admin/manager/create');
        $response->assertStatus(200);
    }

    /**
     * Test get manager list
     * @group feature
     * 
     * @return void
     */
    public function test_index_empty()
    {
        $this->makeUser();

        $response = $this->json('get', '/api/admin/manager');
        $response->assertStatus(200);
    }

    /**
     * 测试管理员界面获取用户数据
     * @group feature
     *
     * @return void
     */
    public function test_get_user_info()
    {
        $this->makeUser();

        $response = $this->get('/api/admin/manager/user/' . $this->user->email);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->user->id
                ]
            ]);

        $response = $this->get('/api/admin/manager/user/' . '1212dsfdsg@qq.com');

        $response->assertStatus(200)
            ->assertJson([
                'data' => null
            ]);
    }
}
