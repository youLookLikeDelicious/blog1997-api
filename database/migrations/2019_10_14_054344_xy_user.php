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
        //
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id')
                ->comment('主键id');
            $table->string('name', 45)
                ->nullable(false)
                ->default('')
                ->comment('用户名');
            $table->string('avatar', 120)
                ->nullable(false)
                ->default('')
                ->comment('头像');
            $table->mediumInteger('article_sum')
                ->default(0)
                ->nullable(false)
                ->comment('创建文章数量');
            $table->enum('is_author', ['yes', 'no'])
                ->nullable(false)
                ->default('no')
                ->comment('是否是作者');
            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');
            $table->tinyInteger('type')
                ->nullable(false)
                ->default(1)
                ->comment('第三方登陆的类型：1 微信，2 github 3 qq');
            $table->unsignedInteger('foreign_id')
                ->nullable(false)
                ->default(0)
                ->comment('第三方平台的id');
            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('name');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE `xy_user` COMMENT = "用户表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('xy_user');
    }
}
