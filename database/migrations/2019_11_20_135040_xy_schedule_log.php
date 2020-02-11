<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyScheduleLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_log', function (Blueprint $table) {
            $table->increments('id')
                ->comment('id');

            $table->tinyInteger('action')
                ->nullable(false)
                ->default(0)
                ->comment('行为: 1-将redis的缓存迁移到数据库中');

            $table->tinyInteger('operated_table')
                ->nullable(false)
                ->default(0)
                ->comment('被操作的表: 1-article 2-comment 3-site_info');

            $table->unsignedInteger('operated_id')
                ->nullable(false)
                ->default(0)
                ->comment('被操作记录的id');

            $table->enum('state', ['success', 'fail'])
                ->nullable(false)
                ->default('success')
                ->comment('执行的状态');

            $table->string('message', 255)
                ->nullable(false)
                ->default('')
                ->comment('额外的提示信息');

            $table->char('date', 10)
                ->nullable(false)
                ->default('')
                ->comment('记录所属的时间');

            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('created_at');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE `xy_schedule_log` COMMENT = "定时任务的日志"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('xy_schedule_log');
    }
}
