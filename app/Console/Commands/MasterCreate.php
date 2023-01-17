<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Notifications\ManagerCreateNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class MasterCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'master:create {email : Input your email} {password : Input your password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create master account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->hasMaster()) {
            $this->warn('There is already a master!');
            return;
        }

        $data = $this->validated();

        // 获取master role
        $role = Role::select('id', 'name')
            ->where('name', 'master')
            ->first();

        $manager = '';

        $data['password'] = Hash::make($data['password']);

        $data['name'] = 'Master';

        DB::transaction(function () use (&$manager, $data, $role) {
            $manager = User::create($data);
            $manager->markEmailAsVerified();
            $manager->roles()->attach($role->id);
        });

        $this->line('Email has been send, please checkout!');
    }


    /**
     * verify email formate
     *
     * @return array
     */
    public function validated()
    {
        $data = [
            'email' => $this->argument('email'),
            'name' => 'Master',
            'password' => $this->argument('password')
        ];

        $validator = Validator::make($data, [
            'email' => 'required|email|unique:user',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->get('email')[0]);
            return [];
        }

        return $validator->validated();
    }

    /**
     * 判断网站内是否有超级管理员
     *
     * @return boolean
     */
    public function hasMaster()
    {
        $user = User::selectRaw('count(id) as count')->first();
        return $user->count;
    }
}
