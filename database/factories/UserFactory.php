<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name . uniqid(),
            'avatar' => '',
            'article_sum' => '0',
            'gender' => 'boy',
            'email' => $this->faker->email . uniqid(),
            'created_at' => time(),
            'updated_at' => time()
        ];
    }
}
