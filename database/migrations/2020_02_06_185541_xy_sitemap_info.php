<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XySitemapInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sitemap_info', function (Blueprint $table) {

            $table->increments('id')->comment('主键');
            
            $table->string('sitemap_url', 225)->unique()->comment('sitemap地址');

            $table->string('mobile', 45)->default('pc,mobile')->comment('网页类型,默认自适应');

            $table->string('changefreq')->nullable(true)->comment('连接可能出现的更新频率');

            $table->decimal('priority', 2, 1)->nullable(true)->comment('连接在网站中的优先级 0 ~ 1');

            $table->tinyInteger('level')->default(1)->comment('sitemap的层级,1级为sitemap索引文件');

            $table->mediumInteger('link_num')->default(0)->comment('该地图中连接的个数');

            $table->integer('parent_id')->nullable(true)->comment('父id');
            
            $table->integer('created_at')->comment('创建时间');

            $table->integer('updated_at')->comment('修改时间');

            $table->index('level');
        });

        DB::statement('ALTER TABLE `xy_sitemap_info` COMMENT = "网站地图信息"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('xy_sitemap_info');
    }
}
