<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class WechatMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_material', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->tinyInteger('type')->default(0)->comment('类型: 0 图文素材封面 1 image 2 voice 3 video 4 thumb 5 article');

            $table->string('media_id', 255)->default('')->comment('media_id');

            $table->string('url', 450)->default('')->comment('素材地址');
            
            $table->unsignedInteger('created_at')->nullable(false)->default(0)->comment('创建时间');

            $table->unsignedInteger('updated_at')->nullable(false)->default(0)->comment('更新时间');
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'wechat_material COMMENT = "微信公众平台素材表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wechat_material');
    }
}
