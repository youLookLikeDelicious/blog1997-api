<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 创建topic表
        Schema::create('topic', function (Blueprint $table) {
            $table->smallIncrements('id')
                ->comment('主键');
            $table->string('name', 45)
                ->nullable(false)
                ->default('')
                ->comment('专题名');
            $table->unsignedSmallInteger('article_sum')
                ->nullable(false)
                ->default(0)
                ->comment('专题下文章的数量');
            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');
            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('name');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE `xy_topic` COMMENT = "专题表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 移除专题表
        Schema::dropIfExists('xy_topic');
    }
}
