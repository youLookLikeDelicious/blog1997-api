<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class MessageBoxFactory extends Factory
{
    public function definition()
    {
        return [
            'sender' => 1,
            'receiver' => 0,
            'content' => 'illegal info',
            'created_at' => time(),
            'updated_at' => time(),
            'reported_id' => 1
        ];
    }
}
