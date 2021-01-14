<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->mediumIncrements('id');

            $table->string('name', 45)
                ->comment('标签的名称');
            
            $table->string('cover', 120)
                ->default('')
                ->comment('封面图片');
            
            $table->mediumInteger('parent_id')
                ->default(0)
                ->comment('父id');

            $table->string('description', 450)
                ->default('')
                ->comment('标签的描述');

            $table->integer('user_id')
                ->default(0)
                ->comment('用户id, 0表示超级管理员创建的，其他的表示是用户自定义标签');

            $table->unsignedInteger('created_at')
            ->comment('创建时间');
            
            $table->unsignedInteger('updated_at')
            ->comment('修改时间');
                
            $table->index('parent_id');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'tags COMMENT = "标签表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
    }
}
