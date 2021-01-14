<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\MessageBox;
use Faker\Generator as Faker;

$factory->define(MessageBox::class, function (Faker $faker) {
    return [
        'sender' => 1,
        'receiver' => 0,
        'content' => 'illegal info',
        'created_at' => time(),
        'updated_at' => time(),
        'reported_id' => 1
    ];
});
