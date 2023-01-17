<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    public function definition()
    {
        return [
            'url' => $this->faker->text(45),
            'created_at' => time(),
            'updated_at' => time()
        ];
    }
}
