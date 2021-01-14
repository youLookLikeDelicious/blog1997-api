<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->tinyIncrements('id');

            $table->enum('enable_comment', ['yes', 'no'])
                ->default('yes')
                ->comment('是否开启评论');

            $table->enum('verify_comment', ['yes', 'no'])
                ->default('no')
                ->comment('是否验证评论');

            $table->unsignedInteger('created_at')
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->comment('修改时间');

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'system_settings COMMENT = "系统设置信息"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
}
