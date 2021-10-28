<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album', function (Blueprint $table) {
            
            $table->increments('id');

            $table->string('name', 120)->comment('相册名称');

            $table->string('desc', 255)->default('')->comment('描述');

            $table->integer('gallery_id')->default(0)->comment('封面id');
            
            $table->integer('user_id')->default(0)->comment('用户id');

            $table->tinyInteger('gallery_is_auto')->default(1)->comment('封面是否自动更新');

            $table->unsignedInteger('created_at')->comment('创建时间');

            $table->unsignedInteger('updated_at')->comment('修改时间');

            $table->index('user_id');
        });

        DB::statement('ALTER TABLE ' . DB::getTablePrefix(). 'album COMMENT = "相册"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('album');
    }
}
