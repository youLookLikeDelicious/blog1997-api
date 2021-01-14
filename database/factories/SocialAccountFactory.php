<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\SocialAccount;
use Faker\Generator as Faker;

$factory->define(SocialAccount::class, function (Faker $faker) {
    return [
        'type' => 1,
        'foreign_id' => 'o2e_ls6h5RB4K5BbfPFt5WxMj4wY',
        'user_id' => 1
    ];
});
