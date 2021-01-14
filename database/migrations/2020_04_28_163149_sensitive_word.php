<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SensitiveWord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sensitive_word', function (Blueprint $table) {
            $table->increments('id');

            $table->string('word', 120)->default('')->comment('关键字');

            $table->mediumInteger('category_id')->nullable(false)->comment('分类id');
            
            $table->unsignedInteger('created_at')
                    ->nullable(false)
                    ->default(0)
                    ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->unique('word');

            $table->index('category_id');
            
            $table->index('created_at');
            
            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'sensitive_word COMMENT = "敏感词汇表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('sensitive_word');
    }
}
