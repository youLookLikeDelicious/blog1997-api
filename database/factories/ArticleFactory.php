<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'is_origin' => 'yes',
            'summary' => $this->faker->text,
            'content' => 'test' . $this->faker->text(1000),
            'liked' => rand(0, 1000),
            'visited' => rand(0, 1000),
            'commented' => rand(0, 1000),
            'user_id' => 1,
            'topic_id' => 1,
            'gallery_id' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ];
    }
}
