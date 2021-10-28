<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyGallery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('gallery', function (Blueprint $table) {
            $table->increments('id')
                ->comment('主键id');
                
            $table->string('url')
                ->nullable(false)
                ->default('')
                ->comment('图片地址');

            $table->text('thumbnail')
                ->nullable()
                ->comment('base64缩略图');

            $table->string('remark')
                ->default('')
                ->comment('对相册的描述');

            $table->enum('is_cover', ['yes', 'no'])
                ->default('no')
                ->comment('是否是封面');

            $table->unsignedInteger('user_id')
                ->default(0)
                ->comment('用户id');

            $table->unsignedInteger('created_at')
                ->default(0)
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->default(0)
                ->comment('更新时间');
                
            $table->index(['user_id', 'is_cover']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'gallery COMMENT = "相册图片"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 删除表
        Schema::dropIfExists('background_image');
    }
}
