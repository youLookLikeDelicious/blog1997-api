<?php

use App\Model\IllegalComment;
use Faker\Generator as Faker;

$factory->define(IllegalComment::class, function (Faker $faker) {
    return [
        'content' => $faker->text(45),
        'created_at' => time(),
        'updated_at' => time()
    ];
});
