<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XySiteInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('site_info', function (Blueprint $table) {
            $table->increments('id')
                ->comment('id');

            $table->unsignedInteger('visited')
                ->nullable(false)
                ->default(0)
                ->comment('当天的访问人数');

            $table->unsignedInteger('registed')
                ->nullable(false)
                ->default(0)
                ->comment('当天注册人数');

            $table->unsignedInteger('record_date')
                ->nullable(false)
                ->default(0)
                ->comment('记录所属时间');

            $table->unsignedInteger('created_at')
                ->nullable(false)
                ->default(0)
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->nullable(false)
                ->default(0)
                ->comment('更新时间');

            $table->index('record_date');
            $table->index('created_at');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE `xy_site_info` COMMENT = "网站的数据统计"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('xy_site_info');
    }
}
