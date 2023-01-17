<?php
namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleBackUpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'is_origin' => 'yes',
            'delete_role'=> 1,
            'summary' => $this->faker->text,
            'content' => $this->faker->text,
            'liked' => rand(0, 1000),
            'visited' => rand(0, 1000),
            'commented' => rand(0, 1000),
            'user_id' => 1,
            'topic_id' => 1,
            'gallery_id' => 1,
            'created_at' => time(),
            'updated_at' => time(),
            'deleted_at' => time()
        ];
    }
}
