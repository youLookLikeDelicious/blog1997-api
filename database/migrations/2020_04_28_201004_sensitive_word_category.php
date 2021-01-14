<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SensitiveWordCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sensitive_word_category', function (Blueprint $table) {
            $table->tinyIncrements('id');

            $table->string('name', 120)->default('')->comment('分类名称');

            $table->tinyInteger('rank')->nullable(false)->comment('屏蔽级别 1 替换 2 审批 3 屏蔽');
            
            $table->unsignedInteger('created_at')
                    ->nullable(false)
                    ->default(0)
                    ->comment('创建时间');

            $table->integer('count')->default(0)->comment('敏感词汇的数量');
            
            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('name');
            
            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'sensitive_word_category COMMENT = "敏感信息分类表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('sensitive_word_category');
    }
}
