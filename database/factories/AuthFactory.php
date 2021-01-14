<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Auth;
use Faker\Generator as Faker;

$factory->define(Auth::class, function (Faker $faker) {
    return [
        'name' => $faker->text(15),
        'parent_id' => 0,
        'auth_path' => '1'
    ];
});
