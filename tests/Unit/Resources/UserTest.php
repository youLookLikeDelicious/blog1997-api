<?php

namespace Tests\Unit\Resources;

use App\Http\Resources\User as ResourcesUser;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     * @group unit
     *
     * @return void
     */
    public function test_user_resource()
    {
        // 创建一个用户
        $user = User::factory()->create();
        $resource = new ResourcesUser($user);
        $resource = $resource->toArray(null);

        $this->assertTrue(strpos($resource['email'], '***') >= 0);
        
        $this->makeUser();
        $role = Role::factory()->create();
        $user->roles()->attach([$role->id]);
        $resource = new ResourcesUser($this->user);
        $resource = $resource->toArray(null);
        $this->assertTrue(strpos($resource['email'], '***') === false);
    }
}
