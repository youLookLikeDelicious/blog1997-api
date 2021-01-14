<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth', function (Blueprint $table) {
            $table->smallIncrements('id');

            $table->string('name', 15)
                ->default('')
                ->comment('权限名称');

            $table->unsignedSmallInteger('parent_id');

            $table->string('auth_path', 120)
                ->default('')
                ->comment('权限id的路径');

            $table->string('route_name', 45)
                ->default('')
                ->comment('api路由的名称');
            
            $table->unsignedInteger('created_at')
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->comment('修改时间');

            // 定义相关索引
            $table->index('parent_id');

            $table->index('auth_path');

            $table->index('route_name');

            $table->index('name');

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'auth COMMENT = "权限表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('auth');
    }
}
