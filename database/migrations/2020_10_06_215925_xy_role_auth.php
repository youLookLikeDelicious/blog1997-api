<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyRoleAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_auth', function (Blueprint $table) {
            $table->unsignedMediumInteger('role_id');

            $table->unsignedSmallInteger('auth_id');

            $table->index('role_id');

            $table->index('auth_id');

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';

        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'role_auth COMMENT = "角色表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_auth');
    }
}
