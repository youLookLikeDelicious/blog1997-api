<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 创建文章表
        Schema::create('article', function (Blueprint $table) {
           $table->smallIncrements('id')
               ->comment('主键');
           $table->string('title')
               ->nullable(false)
               ->comment('标题');
           $table->enum('is_origin', ['yes', 'no'])
               ->nullable(false)
               ->default('yes')
               ->comment('是否原创');
           $table->unsignedTinyInteger('order_by')
               ->nullable(false)
               ->default(50)
               ->comment('排序的权重');
           $table->unsignedTinyInteger('order_by_expire')
               ->nullable(false)
               ->default(0)
               ->comment('权重到期的事件，单位s。到期后会设置成默认值');
           $table->text('summary')
               ->nullable(false)
               ->comment('文章的摘要');
           $table->text('content')
               ->nullable(false)
               ->comment('文章内容');
           $table->unsignedInteger('visited')
               ->nullable(false)
               ->default(0)
               ->comment('访问人数');
           $table->unsignedSmallInteger('commented')
               ->nullable(false)
               ->default(0)
               ->comment('评论数');
           $table->unsignedSmallInteger('user_id')
               ->nullable(false)
               ->comment('用户id');
           $table->unsignedSmallInteger('topic_id')
               ->nullable(false)
               ->comment('专题id');
           $table->unsignedSmallInteger('background_image_id')
               ->nullable()
               ->comment('背景图片');
           $table->unsignedInteger('deleted_at')
               ->nullable()
               ->comment('软删除的标志');
           $table->unsignedInteger('created_at')
               ->nullable(false)
               ->default(0)
               ->comment('创建时间');
           $table->unsignedInteger('updated_at')
               ->nullable(false)
               ->default(0)
               ->comment('更新时间');

            // 添加索引
            $table->index('title');
            $table->index('topic_id');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('order_by');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE `xy_article` COMMENT = "文章表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 移除文章表
        Schema::dropIfExists('xy_article');
    }
}
