<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IllegalComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         // 创建文章备份表
         Schema::create('illegal_comment', function (Blueprint $table) {

            $table->smallIncrements('id')
                ->comment('主键');
            
            $table->integer('comment_id')->comment('违规的评论ID');

            $table->text('content')->comment('之前的评论内容');
 
            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');
 
            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');
 
             // 添加索引
 
             $table->engine = 'InnoDB';
             $table->charset = 'utf8mb4';
         });
 
         DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'illegal_comment COMMENT = "违规的评论表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('illegal_comment');
    }
}
