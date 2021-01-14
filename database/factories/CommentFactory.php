<?php

use App\Model\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'level' => 1,
        'root_id' => 1,
        'reply_to' => '1',
        'able_id' => 1,
        'able_type' => 'App\Model\Article',
        'content' => 'content'
    ];
});

$factory->state(Comment::class, 'comment', [
    'able_type' => 'App\Model\Comment',
]);

$factory->state(Comment::class, 'Blog1997', [
    'able_type' => 'Blog1997',
    'able_id' => 0,
    'level' => 1,
    'reply_to' => 0
]);
$factory->state(Comment::class, 'level-2', [
    'level' => 2
]);
$factory->state(Comment::class, 'level-3', [
    'level' => 3
]);

$factory->state(Comment::class, 'no-verified', [
    'verified' => 'no'
]);