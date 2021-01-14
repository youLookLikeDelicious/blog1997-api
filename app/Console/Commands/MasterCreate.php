<?php

namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Model\Role;
use App\Notifications\ManagerCreateNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class MasterCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'master:create {email : Input your email}';

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
        $data = $this->validated();

        if ($this->hasMaster()) {
            $this->error('There is already one master!');
            return;
        }

        // 获取master role
        $role = Role::select('id', 'name')
            ->where('name', 'master')
            ->first();

        $manager = '';

        DB::transaction(function () use (&$manager, $data, $role) {
            $manager = User::create($data);
            $manager->roles()->attach($role->id);
        });

        $manager->sendEmailVerificationNotification();

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
            'name' => 'Master'
        ];

        $validator = Validator::make($data, [
            'email' => 'required|email|unique:user'
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
