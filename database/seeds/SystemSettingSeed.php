<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_settings')->insert([
            'enable_comment' => 'yes',
            'verify_comment' => 'no',
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }
}
