<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Topic::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'created_at' => time(),
        'updated_at' => time()
    ];
});
