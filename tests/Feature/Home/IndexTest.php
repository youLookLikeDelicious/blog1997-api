<?php

namespace Tests\Feature\Home;

use Tests\TestCase;
use App\Models\Topic;
use App\Models\Article;
use App\Models\ThumbUp;
use App\Models\FriendLink;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @group feature
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $this->makeUser('master');

        Topic::factory()->make([
            'id' => '1',
            'user_id' => $this->user->id
        ]);

        Article::query()->truncate();
        
        $state = ['to' => $this->user->id];
        Article::factory()->count(2)
            // ->has(Gallery::factory())
            ->has(ThumbUp::factory()->state(fn () => $state), 'thumbs')
            ->create([
                'user_id' => $this->user->id
            ]);

        $response = $this->json('get', '/api');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' =>
                    ['identity', 'author' => ['id', 'name', 'avatar'], 'created_at', 'gallery', 'gallery_id', 'is_origin', 'summary', 'title', 'updated_at', 'user_id', 'visited']
                ],
                'popArticles' => [
                    '*' => ['identity', 'title', 'visited']
                ]
            ]);
    }

    /**
     * 测试获取友链
     * @group feature
     * 
     * @return void
     */
    public function test_get_friend_link()
    {
        factory(FriendLink::class, 10)->create();

        $response = $this->json('GET', '/api/friend-link');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'url']
                ]
            ]);
    }
}
