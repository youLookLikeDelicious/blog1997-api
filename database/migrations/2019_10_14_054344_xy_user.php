<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id')
                ->comment('主键id');

            $table->string('name', 45)
                ->nullable(false)
                ->default('')
                ->comment('用户名');

            $table->string('avatar', 210)
                ->nullable(false)
                ->default('')
                ->comment('头像');

            $table->string('email', 120)
                ->default('')
                ->comment('用户邮箱');

            $table->mediumInteger('article_sum')
                ->default(0)
                ->nullable(false)
                ->comment('创建文章数量');

            $table->enum('gender', ['boy', 'girl', 'keep_secret'])
                ->default('keep_secret')
                ->comment('性别');

            $table->char('remember_token', 100)
                ->nullable()
                ->comment('remember token');
            
            $table->char('password', 255)
                ->default('')
                ->comment('密码');

            $table->dateTime('email_verified_at')
                ->nullable()
                ->comment('邮箱验证的时间');
                
            $table->unsignedInteger('deleted_at')
                ->default(0)
                ->comment('软删除时间');
            
            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('name');
            $table->index('email');
            $table->index('deleted_at');
            $table->index('email_verified_at');
            $table->index('created_at');
            
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'user COMMENT = "用户表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
