<?php

namespace Tests\Feature\Home;

use App\Model\FriendLink;
use Tests\TestCase;
use App\Model\Gallery;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

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

        $topic = factory(\App\Model\Topic::class)->make([
            'id' => '1',
            'user_id' => $this->user->id
        ]);

        \App\Model\Article::query()->truncate();
        
        $articles = factory(\App\Model\Article::class, 2)
            ->create([
                'user_id' => $this->user->id
            ])
            ->each(function ($article) {
                $article->thumbs()->save(factory(\App\Model\ThumbUp::class)->make(['to' => $this->user->id]));
                if (Gallery::find($article->gallery_id)) {
                    return;
                }
                $article->gallery()->save(factory(\App\Model\Gallery::class)->make());
            });

        $response = $this->json('get', '/api');

        $response->assertStatus(200)
            ->assertJson(['message' => 'success'])
            ->assertJson(['status' =>  'success'])
            ->assertJson([
                'data' => [
                    'articleNum' => 2
                ]
            ])
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [
                    'articles' => [
                        '*' =>
                        ['identity', 'author' => ['id', 'name', 'avatar'], 'created_at', 'gallery' => ['id', 'url'], 'gallery_id', 'is_origin', 'summary', 'title', 'updated_at', 'user_id', 'visited']
                    ],
                    'articleNum',
                    'messageNum',
                    'pages',
                    'popArticles'
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
