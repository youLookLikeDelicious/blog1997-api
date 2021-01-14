<?php

use Faker\Generator as Faker;
use App\Model\ArticleBackUp;

$factory->define(ArticleBackUp::class, function (Faker $faker) {
    $text = $faker->text(45);

    return [
        'title' => $faker->title,
        'is_origin' => 'yes',
        'delete_role'=> 1,
        'summary' => $text,
        'content' => $text,
        'liked' => rand(0, 1000),
        'visited' => rand(0, 1000),
        'commented' => rand(0, 1000),
        'user_id' => 1,
        'topic_id' => 1,
        'gallery_id' => 1,
        'created_at' => time(),
        'updated_at' => time(),
        'deleted_at' => time(),
        'id' => mt_rand(1, 100)
    ];
});
