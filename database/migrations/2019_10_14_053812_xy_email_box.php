<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyEmailBox extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('email_box', function (Blueprint $table) {
            $table->smallIncrements('id')
                ->comment('主键');
            $table->text('content')
                ->nullable(false)
                ->comment('邮件内容');
            $table->unsignedSmallInteger('user_id')
                ->nullable(false)
                ->default(0)
                ->comment('发送人id');
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

        DB::statement('ALTER TABLE `xy_email_box` COMMENT = "留言表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('xy_email_box');
    }
}
