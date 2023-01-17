<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ThumbUpFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => rand(1, 10),
            'able_id' => rand(1, 10),
            'able_type' => 'article',
            'created_at' => time(),
            'updated_at' => time()
        ];
    }
}
