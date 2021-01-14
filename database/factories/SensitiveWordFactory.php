<?php

use App\Model\SensitiveWord;
use Faker\Generator as Faker;

$factory->define(SensitiveWord::class, function (Faker $faker) {
    return [
        'category_id' => '1',
        'word' => $faker->name,
        'created_at' => time(),
        'updated_at' => time()
    ];
});
