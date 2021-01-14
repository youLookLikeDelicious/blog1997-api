<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('user_id')
                ->default(0)
                ->comment('绑定的用户id');

            $table->tinyInteger('type')
                ->nullable(false)
                ->default(1)
                ->comment('第三方登陆的类型：1 微信，2 github 3 qq');

            $table->string('foreign_id', 225)
                ->nullable(false)
                ->default(0)
                ->comment('第三方平台的id,如果是微信用户，就是unionid');

            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('user_id');
            $table->index('foreign_id');
            $table->index('created_at');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'social_accounts COMMENT = "第三方账号关联表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_accounts');
    }
}
