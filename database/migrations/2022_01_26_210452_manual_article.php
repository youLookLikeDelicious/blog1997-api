<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ManualArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255)->default('')->comment('标题');
            $table->text('content')->comment('内容');
            $table->enum('is_markdown', ['yes', 'no'])->default('yes')->commit('是否是markdown语法');
            $table->unsignedBigInteger('manual_id')->default(0)->comment('手册id');
            $table->unsignedBigInteger('catalog_id')->default(0)->comment('目录ID');
            $table->unsignedInteger('liked')->default(0)->comment('点赞次数');
            $table->unsignedInteger('visited')->default(0)->comment('访问次数');
            $table->unsignedInteger('commented')->default(0)->comment('评论次数');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('修改时间');
            $table->unsignedInteger('deleted_at')->nullable()->comment('删除时间');

            $table->index('manual_id');
            $table->index('catalog_id');
        });
        
        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'article ADD FULLTEXT index article_full_text_index(title, content) WITH PARSER ngram');

        DB::statement('ALTER TABLE ' . DB::getTablePrefix(). 'manual_articles COMMENT = "手册文章表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_articles');
    }
}
