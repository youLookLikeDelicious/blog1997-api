<?php
namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\SensitiveWordCategory;

$factory->define(SensitiveWordCategory::class, function (Faker $faker) {
    return [
        'count' => 0,
        'name' => $faker->name,
        'rank' => mt_rand(1, 3),
        'created_at' => time(),
        'updated_at' => time(),
    ];
});
