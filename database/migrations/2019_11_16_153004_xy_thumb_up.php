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

            $table->unsignedInteger('thumbable_id')
                ->nullable(false)
                ->default(0)
                ->comment('被评论的id');

            $table->string('thumbable_type', 36)
                ->nullable(false)
                ->default('')
                ->comment('被评论的的模型');

            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('user_id');
            $table->index('thumbable_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';

        });

        DB::statement('ALTER TABLE `xy_thumb_up` COMMENT = "点赞记录"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('xy_thumb_up');
    }
}
