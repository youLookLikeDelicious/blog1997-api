<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryAlbum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_album', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('gallery_id')->comment('图片id');

            $table->integer('album_id')->comment('相册id');

            $table->index('gallery_id');

            $table->index('album_id');
        });

        DB::statement('ALTER TABLE ' . DB::getTablePrefix(). 'album COMMENT = "相册和照片关系表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gallery_album');
    }
}
