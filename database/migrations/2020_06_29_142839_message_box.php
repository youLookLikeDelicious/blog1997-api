<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MessageBox extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 创建一个邮箱表
        Schema::create('message_box', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->integer('sender')->comment('发出消息的用户id');

            $table->integer('receiver')->comment('收到消息的用户id,0表示发送给站长');

            $table->string('type', 45)->comment('相关的模型 article comment thumbup');

            $table->string('content', 2100)->default('')->comment('具体内容');
            
            $table->integer('reported_id')->default(0)->comment('被举报记录的id');
            
            $table->integer('root_comment_id')->default(0)->comment('如果通知的是评论，表示一级评论的id');

            $table->enum('have_read', ['yes', 'no'])->default('no');

            $table->enum('operate', ['undo', 'approve', 'ignore'])->default('undo')->comment('执行的操作,在处理举报信息的时候使用');
                
            $table->unsignedInteger('created_at')->nullable(false)->default(0)->comment('创建时间');

            $table->unsignedInteger('deleted_at')->nullable()->default(null)->comment('删除时间');
            
            $table->unsignedInteger('updated_at')->nullable(false)->default(0)->comment('更新时间');

            $table->index('sender');

            $table->index('receiver');

            $table->index('reported_id');

            $table->index(['have_read', 'receiver', 'type']);
            
            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'message_box COMMENT = "邮件表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 移除该表
        Schema::dropIfExists('message_box');
    }
}
