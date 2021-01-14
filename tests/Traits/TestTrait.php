<?php
namespace Tests\Traits;

use App\Model\Role;
use App\Model\User;
use Illuminate\Support\Facades\Auth;

trait TestTrait {
    /**
     * 用户模型
     * @var \App\Model\User
     */
    public $user;

    /**
     * @param string $status
     * 
     * @return void
     */
    public function makeUser ($states = '') {
        if ($states === 'master') {
            $this->user = factory(User::class)->states($states)->create([
                'email' => '123123@qq.com'
            ]);
            $role = factory(Role::class)->create(['name' => 'Master']);
            $this->user->roles()->sync([$role->id]);
        } else {
            $this->user = factory(User::class)->create();
        }
        
        Auth::login($this->user);
    }
}