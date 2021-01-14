<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_roles', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')
                ->default(0)
                ->comment('管理员id');

            $table->unsignedMediumInteger('role_id')
                ->default(0)
                ->comment('角色id');

            $table->timestamps();

            $table->index('user_id');

            $table->index('role_id');

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'manager_roles COMMENT = "管理员和角色的关系对应表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manager_roles');
    }
}
