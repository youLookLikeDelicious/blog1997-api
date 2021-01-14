<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XyRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->mediumIncrements('id');

            $table->string('name', 45)
                ->default('')
                ->comment('角色名称');

            $table->string('remark', 450)
                ->default('')
                ->comment('备注');
                
            $table->unsignedInteger('created_at')
                ->comment('创建时间');

            $table->unsignedInteger('updated_at')
                ->comment('修改时间');

            $table->unique('name');

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
        });

        DB::statement('ALTER TABLE ' .DB::getTablePrefix(). 'role COMMENT = "角色表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role');
    }
}
