<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoFeildToArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article', function (Blueprint $table) {
            // 添加keywords字段和description字段
            $table->string('keywords', 210)
                ->nullable(false)
                ->default('')
                ->after('order_by_expire')
                ->comment('用于SEO的关键字');
            $table->string('description', 330)
                ->nullable(false)
                ->default('')
                ->after('keywords')
                ->comment('用于SEO的描述');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article', function (Blueprint $table) {
            //
            $table->dropColumn('keywords');
            $table->dropColumn('description');
        });
    }
}
