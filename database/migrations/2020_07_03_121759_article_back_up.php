<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArticleBackUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 创建文章备份表
        Schema::create('article_back_up', function (Blueprint $table) {

            $table->smallIncrements('id')
                ->comment('主键');

            $table->string('title', 72)
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

            $table->text('summary')
                ->nullable(false)
                ->comment('文章的摘要');

            $table->text('content')
                ->nullable(false)
                ->comment('文章内容');

            $table->enum('delete_role', ['user', 'master'])->comment('谁删除的');

            $table->string('delete_reason')->default('')->comment('删除的原因');

            $table->unsignedInteger('liked')
                ->nullable(false)
                ->default('0')
                ->comment('点赞数');

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

            $table->unsignedSmallInteger('gallery_id')
                ->nullable()
                ->comment('背景图片');

            $table->enum('is_markdown', ['yes', 'no'])
                ->default('no')
                ->comment('是否是markdown语法');

            $table->unsignedInteger('deleted_at')
                ->comment('删除的时间');

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
            $table->index('liked');
            $table->index(['delete_role', 'user_id']);
            $table->index('commented');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'article_back_up COMMENT = "保存被删除的文章"');
        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'article_back_up ADD FULLTEXT index article_full_text_index(title, content)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 移除文章表
        Schema::dropIfExists('article_back_up');
    }
}
