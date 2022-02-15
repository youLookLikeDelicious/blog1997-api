<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manuals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 45)->comment('名称');
            $table->text('summary')->nullable()->comment('摘要');
            $table->integer('user_id')->comment('用户ID');
            $table->tinyInteger('is_public')->default(1)->comment('是否公开 1: 是 2: 否');
            $table->string('cover', 255)->default('')->comment('封面');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('修改时间');
            $table->unsignedInteger('deleted_at')->nullable()->comment('删除时间');
            $table->index('user_id');

        });
        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'manuals ADD FULLTEXT index article_full_text_index(name, summary) WITH PARSER ngram');
        DB::statement('ALTER TABLE ' . DB::getTablePrefix(). 'manuals COMMENT = "手册表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manuals');
    }
}
