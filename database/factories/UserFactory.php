<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Model\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name . uniqid(),
        'avatar' => '',
        'article_sum' => '0',
        'gender' => 'boy',
        'email' => $faker->email . uniqid(),
        'created_at' => time(),
        'updated_at' => time()
    ];
});

$factory->state(App\Model\User::class, 'author', [
    // 'role' => 'author'
]);

$factory->state(App\Model\User::class, 'master', [
    // 'role' => 'master'
]);
