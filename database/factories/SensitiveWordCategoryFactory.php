<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Model\SensitiveWordCategory;

$factory->define(SensitiveWordCategory::class, function (Faker $faker) {
    return [
        'count' => 0,
        'name' => $faker->name,
        'rank' => mt_rand(1, 3),
        'created_at' => time(),
        'updated_at' => time(),
    ];
});
