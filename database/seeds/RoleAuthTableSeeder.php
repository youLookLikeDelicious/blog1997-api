<?php
use Illuminate\Database\Seeder;

class RoleAuthTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        if (\DB::table('role_auth')->count()) {
            return;
        }

        \DB::table('role_auth')->delete();

        \DB::table('role_auth')->insert(array (
            0 =>
            array (
                'role_id' => 1,
                'auth_id' => 1,
            ),
            1 =>
            array (
                'role_id' => 1,
                'auth_id' => 13,
            ),
            2 =>
            array (
                'role_id' => 1,
                'auth_id' => 14,
            ),
            3 =>
            array (
                'role_id' => 1,
                'auth_id' => 15,
            ),
            4 =>
            array (
                'role_id' => 1,
                'auth_id' => 16,
            ),
            5 =>
            array (
                'role_id' => 1,
                'auth_id' => 17,
            ),
            6 =>
            array (
                'role_id' => 1,
                'auth_id' => 2,
            ),
            7 =>
            array (
                'role_id' => 1,
                'auth_id' => 6,
            ),
            8 =>
            array (
                'role_id' => 1,
                'auth_id' => 7,
            ),
            9 =>
            array (
                'role_id' => 1,
                'auth_id' => 8,
            ),
            10 =>
            array (
                'role_id' => 1,
                'auth_id' => 9,
            ),
            11 =>
            array (
                'role_id' => 1,
                'auth_id' => 4,
            ),
            12 =>
            array (
                'role_id' => 1,
                'auth_id' => 10,
            ),
            13 =>
            array (
                'role_id' => 1,
                'auth_id' => 11,
            ),
            14 =>
            array (
                'role_id' => 1,
                'auth_id' => 12,
            ),
            15 =>
            array (
                'role_id' => 1,
                'auth_id' => 5,
            ),
            16 =>
            array (
                'role_id' => 1,
                'auth_id' => 18,
            ),
            17 =>
            array (
                'role_id' => 1,
                'auth_id' => 19,
            ),
            18 =>
            array (
                'role_id' => 1,
                'auth_id' => 36,
            ),
            19 =>
            array (
                'role_id' => 1,
                'auth_id' => 37,
            ),
            20 =>
            array (
                'role_id' => 1,
                'auth_id' => 38,
            ),
            21 =>
            array (
                'role_id' => 1,
                'auth_id' => 39,
            ),
            22 =>
            array (
                'role_id' => 1,
                'auth_id' => 40,
            ),
            23 =>
            array (
                'role_id' => 1,
                'auth_id' => 41,
            ),
            24 =>
            array (
                'role_id' => 1,
                'auth_id' => 42,
            ),
            25 =>
            array (
                'role_id' => 1,
                'auth_id' => 43,
            ),
            26 =>
            array (
                'role_id' => 1,
                'auth_id' => 44,
            ),
            27 =>
            array (
                'role_id' => 1,
                'auth_id' => 45,
            ),
            28 =>
            array (
                'role_id' => 1,
                'auth_id' => 46,
            ),
            29 =>
            array (
                'role_id' => 1,
                'auth_id' => 47,
            ),
            30 =>
            array (
                'role_id' => 1,
                'auth_id' => 48,
            ),
            31 =>
            array (
                'role_id' => 1,
                'auth_id' => 49,
            ),
            32 =>
            array (
                'role_id' => 1,
                'auth_id' => 50,
            ),
            33 =>
            array (
                'role_id' => 1,
                'auth_id' => 51,
            ),
            34 =>
            array (
                'role_id' => 1,
                'auth_id' => 52,
            ),
            35 =>
            array (
                'role_id' => 1,
                'auth_id' => 53,
            ),
            36 =>
            array (
                'role_id' => 1,
                'auth_id' => 54,
            ),
            37 =>
            array (
                'role_id' => 1,
                'auth_id' => 56,
            ),
            38 =>
            array (
                'role_id' => 1,
                'auth_id' => 68,
            ),
            39 =>
            array (
                'role_id' => 1,
                'auth_id' => 70,
            ),
            40 =>
            array (
                'role_id' => 1,
                'auth_id' => 69,
            ),
            41 =>
            array (
                'role_id' => 1,
                'auth_id' => 74,
            ),
            42 =>
            array (
                'role_id' => 1,
                'auth_id' => 75,
            ),
            43 =>
            array (
                'role_id' => 1,
                'auth_id' => 64,
            ),
            44 =>
            array (
                'role_id' => 1,
                'auth_id' => 65,
            ),
            45 =>
            array (
                'role_id' => 1,
                'auth_id' => 66,
            ),
            46 =>
            array (
                'role_id' => 1,
                'auth_id' => 67,
            ),
            47 =>
            array (
                'role_id' => 1,
                'auth_id' => 73,
            ),
            48 =>
            array (
                'role_id' => 1,
                'auth_id' => 76,
            ),
            49 =>
            array (
                'role_id' => 1,
                'auth_id' => 77,
            ),
            50 =>
            array (
                'role_id' => 1,
                'auth_id' => 78,
            ),
            51 =>
            array (
                'role_id' => 1,
                'auth_id' => 79,
            ),
            52 =>
            array (
                'role_id' => 1,
                'auth_id' => 80,
            ),
            53 =>
            array (
                'role_id' => 1,
                'auth_id' => 81,
            ),
            54 =>
            array (
                'role_id' => 1,
                'auth_id' => 61,
            ),
            55 =>
            array (
                'role_id' => 1,
                'auth_id' => 62,
            ),
            56 =>
            array (
                'role_id' => 1,
                'auth_id' => 63,
            ),
            57 =>
            array (
                'role_id' => 1,
                'auth_id' => 60,
            ),
            58 =>
            array (
                'role_id' => 1,
                'auth_id' => 22,
            ),
            59 =>
            array (
                'role_id' => 1,
                'auth_id' => 26,
            ),
            60 =>
            array (
                'role_id' => 1,
                'auth_id' => 27,
            ),
            61 =>
            array (
                'role_id' => 1,
                'auth_id' => 28,
            ),
            62 =>
            array (
                'role_id' => 1,
                'auth_id' => 29,
            ),
            63 =>
            array (
                'role_id' => 1,
                'auth_id' => 30,
            ),
            64 =>
            array (
                'role_id' => 1,
                'auth_id' => 21,
            ),
            65 =>
            array (
                'role_id' => 1,
                'auth_id' => 23,
            ),
            66 =>
            array (
                'role_id' => 1,
                'auth_id' => 24,
            ),
            67 =>
            array (
                'role_id' => 1,
                'auth_id' => 25,
            ),
            68 =>
            array (
                'role_id' => 1,
                'auth_id' => 31,
            ),
            69 =>
            array (
                'role_id' => 1,
                'auth_id' => 32,
            ),
            70 =>
            array (
                'role_id' => 1,
                'auth_id' => 33,
            ),
            71 =>
            array (
                'role_id' => 1,
                'auth_id' => 34,
            ),
            72 =>
            array (
                'role_id' => 1,
                'auth_id' => 35,
            ),
            73 =>
            array (
                'role_id' => 1,
                'auth_id' => 20,
            ),
            74 =>
            array (
                'role_id' => 1,
                'auth_id' => 59,
            ),
            95 =>
            array (
                'role_id' => 1,
                'auth_id' => 83,
            ),
            96 =>
            array (
                'role_id' => 1,
                'auth_id' => 82,
            ),
        ));
    }
}
