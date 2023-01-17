<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyThumbUp extends Migration
{
    /**
     * Run the migrations.
     * 生成点赞表
     * @return void
     */
    public function up()
    {
        Schema::create('thumb_up', function (Blueprint $table) {

            $table->increments('id')
                ->comment('id');

            $table->unsignedInteger('user_id')
                ->nullable(false)
                ->default(0)
                ->comment('用户id');

            $table->unsignedInteger('able_id')
                ->nullable(false)
                ->default(0)
                ->comment('被评论的id');

            $table->string('title', 1)
                ->default('');

            $table->unsignedSmallInteger('content')
                ->default(1)
                ->comment('被点赞的次数');
                
            $table->string('able_type', 45)->comment('被评论的的模型 article comment');

            $table->integer('to')
                ->comment('收到赞的用户');

            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('user_id');
            $table->index('to');
            $table->index('able_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';

        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'thumb_up COMMENT = "点赞记录"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('thumb_up');
    }
}
