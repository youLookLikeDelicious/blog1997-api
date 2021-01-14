<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Log;
use Faker\Generator as Faker;

$factory->define(Log::class, function (Faker $faker) {
    return [
        'result' => 'success',
        'message' => '登陆成功'
    ];
});
