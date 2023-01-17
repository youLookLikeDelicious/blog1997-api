<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IllegalCommentFactory extends Factory
{
    public function definition()
    {
        return [
            'content' => $this->faker->text(45),
            'created_at' => time(),
            'updated_at' => time()
        ];
    }
}
