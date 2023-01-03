<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        if (\DB::table('role')->count()) {
            return;
        }

        \DB::table('role')->delete();

        \DB::table('role')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Master',
                'remark' => 'Supper admin',
                'created_at' => 1602561048,
                'updated_at' => 1602561156,
            )
        ));


    }
}
