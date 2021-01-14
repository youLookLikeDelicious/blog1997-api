<?php

namespace Tests\Unit\Repository;

use App\Model\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repository\User;
use App\Model\SocialAccount;
use App\Model\User as ModelUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Email string
     *
     * @var string
     */
    protected $email = 'blog1997@163.com';

    /**
     * A basic unit test for user repository.
     * @group unit
     *
     * @return void
     */
    public function test_when_there_is_no_user()
    {
        $user = $this->getRepository()->findByVendorInfo(12, '2');

        $this->assertNull($user);
    }

    /**
     * Get user repository
     *
     * @return User
     */
    protected function getRepository()
    {
        return app()->make(User::class);
    }

    /**
     * A basic unit test for user repository.
     * @group unit
     *
     * @return void
     */
    public function test_when_there_is_a_user()
    {
        
        $user = factory(ModelUser::class)->create([
        ]);
            
        factory(SocialAccount::class)->create([
            'user_id' => $user->id,
            'foreign_id' => 12,
            'type' => '2'
        ]);

        $repositoryUser = $this->getRepository()->findByVendorInfo(12, '2');

        $this->assertEquals($repositoryUser->id, $user->id);
    }

    /**
     * Test find by email function
     * @group unit
     *
     * @return void
     */
    public function test_find_by_email()
    {
        $repository = $this->getRepository();

        // 获取不存在的user
        $user = $repository->findByEmail('');

        $this->assertNull($user);

        factory(ModelUser::class)->create([
            'email' => $this->email
        ]);

        // 获取存在的user
        $user = $repository->findByEmail($this->email);

        $this->assertNotNull($user);
    }

    /**
     * 测试获取管理员
     * @group unit
     *
     * @return void
     */
    public function test_get_manager()
    {
        $repository = $this->getRepository();

        // 当数据为空时,测试获取的数据
        $request = new Request(['email' => $this->email]);
        $managers = $repository->getManagers($request);

        $this->assertEmpty($managers['records']);

        // 添加用户
        $user = factory(ModelUser::class)->create([
            'email' => $this->email
        ]);

        // 测试没有管理员的情况
        $request = new Request(['email' => $this->email]);
        $managers = $repository->getManagers($request);
        $this->assertEquals(0, $managers['records']->count());

        // 添加角色
        $role = factory(Role::class)->create([
            'name' => 'role'
        ]);

        $user->roles()->sync([$role->id]);
        // 测试有管理员的情况
        $request = new Request(['email' => $this->email]);
        $managers = $repository->getManagers($request);
        $this->assertEquals(1, $managers['records']->count());
    }

    /**
     * 测试根据平台统计用户
     * @group unit
     *
     * @return void
     */
    public function test_statistic()
    {
        $repository = $this->getRepository();

        // 测试数据为空的情况
        $data = $repository->statisticBySource();
        $this->assertEquals(0, $data->count());

        // 添加几个第三方账号
        factory(SocialAccount::class, 20)->create([
            'type' => 1
        ]);
        factory(SocialAccount::class, 10)->create([
            'type' => 2
        ]);
        // 测试统计
        $data = $repository->statisticBySource();
        $this->assertEquals(20, $data[0]->count);
        $this->assertEquals(10, $data[1]->count);
    }
}
