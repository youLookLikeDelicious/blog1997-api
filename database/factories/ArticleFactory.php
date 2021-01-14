<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Article::class, function (Faker $faker) {
    $text = $faker->text;
    return [
        'title' => $faker->title,
        'is_origin' => 'yes',
        'summary' => $text,
        'content' => 'test' . $faker->text(1000),
        'liked' => rand(0, 1000),
        'visited' => rand(0, 1000),
        'commented' => rand(0, 1000),
        'user_id' => 1,
        'topic_id' => 1,
        'gallery_id' => 1,
        'created_at' => time(),
        'updated_at' => time()
    ];
});
