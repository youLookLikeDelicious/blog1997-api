<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 创建评论表
        Schema::create('comment', function (Blueprint $table) {

            $table->increments('id')
                ->comment('主键');

            $table->string('title', 1)
                ->default('');
                
            $table->string('content', 2100)
                ->nullable(false)
                ->comment('评论内容');

            $table->unsignedInteger('root_id')
                ->nullable(false)
                ->default(0)
                ->comment('一级评论的id');

            $table->unsignedInteger('able_id')
                ->nullable(false)
                ->default(0)
                ->comment('被评论的id');

            $table->string('able_type', 45)
                ->comment('被回复的模型');

            $table->unsignedMediumInteger('commented')
                ->nullable(false)
                ->default(0)
                ->comment('回复的数量');

            $table->unsignedInteger('liked')
                ->default('0')
                ->comment('点赞数');

            $table->unsignedInteger('article_id')
                ->default(0)
                ->comment('如果是文章的评论，对应文章的id');

            $table->unsignedMediumInteger('user_id')
                ->nullable(false)
                ->default(0)
                ->comment('用户id');

            $table->unsignedMediumInteger('reply_to')
                ->nullable(false)
                ->default(0)
                ->comment('被回复用户的id,三级评论需要');

            $table->enum('verified', ['yes', 'no'])
                ->default('yes')
                ->comment('审核是否通过');

            $table->unsignedTinyInteger('level')
                ->nullable(false)
                ->default(1)
                ->comment('评论的层级，最高为三级');

            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->unsignedBigInteger('deleted_at')->nullable()->default(null);
            
            $table->index('able_id');
            $table->index('able_type');
            $table->index('root_id');
            $table->index('liked');
            $table->index('article_id');
            $table->index(['verified', 'created_at']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'comment COMMENT = "评论表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('comment');
    }
}
