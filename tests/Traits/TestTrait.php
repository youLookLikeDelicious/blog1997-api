<?php
namespace Tests\Traits;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait TestTrait {
    /**
     * 用户模型
     * @var \App\Models\User
     */
    public $user;

    /**
     * @param string $status
     * 
     * @return void
     */
    public function makeUser ($states = '') {
        if ($states === 'master') {
            $this->user = User::factory()->has(Role::factory()->suspended('Master'))->create();
        } else {
            $this->user = User::factory()->create();
        }
        
        Auth::login($this->user);
    }
}