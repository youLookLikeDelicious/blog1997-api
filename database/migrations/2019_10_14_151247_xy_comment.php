<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 创建评论表
        Schema::create('comment', function (Blueprint $table) {
            $table->smallIncrements('id')
                ->comment('主键');
            $table->text('content')
                ->nullable(false)
                ->comment('评论内容');
            $table->string('comment_path', 999)
                ->nullable(false)
                ->comment('评论的全路径');
            $table->unsignedSmallInteger('commentable_id')
                ->nullable(false)
                ->default(0)
                ->comment('被评论的id');
            $table->string('commentable_type', '99')
                ->nullable(false)
                ->default('')
                ->comment('评论的类型');
            $table->unsignedSmallInteger('user_id')
                ->nullable(false)
                ->default(0)
                ->comment('用户id');
            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');
            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('commentable_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE `xy_comment` COMMENT = "评论表"');
        DB::statement('ALTER TABLE `xy_comment` ADD INDEX comment_path (comment_path(255))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('xy_comment');
    }
}
