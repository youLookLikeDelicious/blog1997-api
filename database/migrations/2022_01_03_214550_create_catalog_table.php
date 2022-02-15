<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 45)->comment('目录名称');
            $table->tinyInteger('level')->default(0)->comment('当前层级,默认0');
            $table->tinyInteger('type')->default(1)->comment('类型: 1标题, 2文章');
            $table->bigInteger('pre_node')->default(0)->comment('前一个节点');
            $table->bigInteger('next_node')->default(0)->comment('下一个个节点');
            $table->bigInteger('parent_id')->default(0)->comment('父级节点');
            $table->bigInteger('manual_id')->default(0)->comment('手册id');
            $table->tinyInteger('is_public')->default(1)->comment('是否公开 1: 是 2: 否');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('修改时间');
            $table->unsignedInteger('deleted_at')->nullable()->comment('删除时间');
            
            $table->index('manual_id');
        });

        DB::statement('ALTER TABLE ' . DB::getTablePrefix(). 'catalogs COMMENT = "手册目录表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogs');
    }
}
