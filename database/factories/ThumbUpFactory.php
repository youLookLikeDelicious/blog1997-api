<?php

use Faker\Generator as Faker;

$factory->define(App\Model\ThumbUp::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 10),
        'able_id' => rand(1, 10),
        'able_type' => 'App\Model\Article',
        'created_at' => time(),
        'updated_at' => time()
    ];
});
