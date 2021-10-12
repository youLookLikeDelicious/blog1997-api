<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGprsToGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gallery', function (Blueprint $table) {
            $table->string('lng_lat', 45)->default('')->comment('经纬度')->before('created_at');

            $table->string('camera_name', 255)->default('')->comment('相机名称')->before('created_at');

            $table->string('location', 255)->default('')->comment('地理位置')->before('created_at');

            $table->unsignedInteger('date_time')->default('')->comment('拍摄时间')->before('created_at');

            $table->text('colors')->nullable()->comment('文件的颜色列表')->before('created_at');

            $table->string('exposure_time', 120)->default('')->comment('曝光时间')->before('created_at');

            $table->string('focal_length', 120)->default('')->comment('焦距')->before('created_at');

            $table->string('f_number', 120)->default('')->comment('光圈数')->before('created_at');

            $table->unsignedInteger('deleted_at')->nullable()->comment('删除时间')->before('created_at');

            $table->index('date_time');

        });

        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'gallery ADD FULLTEXT index keywords(location, remark) WITH PARSER ngram');
        DB::statement('ALTER TABLE ' . DB::getTablePrefix() . 'gallery ADD FULLTEXT index colors(colors) WITH PARSER ngram');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gallery', function (Blueprint $table) {
            //
        });
    }
}
