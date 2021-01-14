<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_config', function (Blueprint $table) {
            $table->tinyIncrements('id');

            $table->string('driver', 45)
                ->default('')
                ->comment('驱动');

            $table->string('email_server', 120)
                ->default('')
                ->comment('服务器地址');

            $table->string('email_addr', 120)
                ->default('')
                ->comment('邮箱地址');
                
            $table->unsignedSmallInteger('port')
                ->default(0)
                ->comment('服务器端口');

            $table->enum('encryption', ['none', 'ssl', 'tls'])
                ->default('none')
                ->comment('加密方式');

            $table->string('sender', 45)
                ->default('')
                ->comment('发件人');

            $table->string('password', '120')
                ->default('')
                ->comment('认证密码');

            $table->unsignedInteger('created_at')
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->comment('修改时间');

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'email_config COMMENT = "系统设置信息"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_config');
    }
}
