<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Gallery::class, function (Faker $faker) {
    return [
        'url' => $faker->text(45),
        'created_at' => time(),
        'updated_at' => time()
    ];
});
