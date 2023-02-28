<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log', function (Blueprint $table)
        {
            $table->increments('id');
            
            $table->text('message')
                ->nullable()
                ->comment('记录的内容');
            
            $table->enum('level', ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'])
                ->default('info')
                ->comment('日志级别');

            $table->enum('result', ['success', 'failure', 'neutral'])
                ->default('neutral')
                ->comment('日志的结果');

            $table->enum('operate', ['log', 'update', 'delete', 'create', 'login', 'logout', 'register', 'schedule', 'queue'])
                ->default('log')
                ->comment('操作的类型');

            $table->string('request_url', 210)
                ->default('')
                ->comment('请求路径');
                
            $table->unsignedInteger('user_id')
                ->default(0)
                ->comment('用户id');

            $table->unsignedMediumInteger('time_consuming')
                ->default(0)
                ->comment("请求耗时");

            $table->text('user_agent')->nullable()->comment('用户代理');
            
            $table->string('origin', 200)
                ->default('')
                ->comment('HTTP origin');

            $table->ipAddress('ip')
                ->default();
            
            $table->string('port', 45)
                ->default('80')
                ->comment('端口');
            
            $table->string('location', 60)
                ->default('')
                ->comment('ip所在的地理位置');

            $table->unsignedInteger('created_at')
                ->default(0)
                ->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log');
    }
}
