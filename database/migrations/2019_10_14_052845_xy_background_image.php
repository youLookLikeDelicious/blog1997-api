<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyBackgroundImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('background_image', function (Blueprint $table) {
            $table->smallIncrements('id')
                ->comment('主键id');
            $table->string('120')
                ->nullable(false)
                ->default('')
                ->comment('图片地址');
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

        DB::statement('ALTER TABLE `xy_background_image` COMMENT = "背景图片表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 删除表
        Schema::dropIfExists('xy_background_image');
    }
}
