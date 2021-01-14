<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyFriendLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 创建友链表
        Schema::create('friend_link', function (Blueprint $table) {
            $table->smallIncrements('id')
                ->comment('主键id');
            $table->string('name', 45)
                ->nullable(false)
                ->default('')
                ->comment('友链名称');
            $table->string('url')
                ->nullable(false)
                ->default('')
                ->comment('友链的地址');
            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');
            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'friend_link COMMENT = "友链信息表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('friend_link');
    }
}
