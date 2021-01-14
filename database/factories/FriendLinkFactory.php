<?php

use App\Model\FriendLink;
use Faker\Generator as Faker;

$factory->define(FriendLink::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
        'name' => $faker->name
    ];
});
