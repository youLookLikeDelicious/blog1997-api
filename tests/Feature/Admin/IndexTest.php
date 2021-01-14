<?php

namespace Tests\Feature\Admin;

use App\Model\Article;
use App\Model\MessageBox;
use App\Model\Topic;
use App\Model\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test for admin dashboard.
     * @group feature
     *
     * @return void
     */
    public function test_without_data()
    {
        $this->makeUser('author');
        
        $response = $this->get('/api/admin/dashboard');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'userInfo', 'articleInfo', 'illegalInfo', 'totalCommented', 'totalLiked'
                ]
            ]);
    }

    /**
     * A basic feature test for admin dashboard.
     * @group feature
     *
     * @return void
     */
    public function test()
    {
        $this->makeUser('author');
        // 模拟专题-------------------------------------------------------------
        factory(Topic::class)->create([
            'id' => 2
        ]);
        factory(Topic::class)->create([
            'id' => 3
        ]);
        factory(Topic::class)->create([
            'id' => 5
        ]);
        // 模拟文章数据----------------------------------------------------------
        factory(Article::class, 3)->create([
            'topic_id' => 2
        ]);

        factory(Article::class, 9)->create([
            'topic_id' => 3
        ]);

        factory(Article::class, 10)->create([
            'topic_id' => 5
        ]);

        // 模拟用户数据--------------------------------------------------------
        factory(User::class, 10)->create();
        factory(User::class, 12)->create();

        // 模拟举报信息--------------------------------------------------------
        factory(MessageBox::class)->create([
            'type' => 'App\Model\Comment',
            'receiver' => '-1'
        ]);

        factory(MessageBox::class, 10)->create([
            'type' => 'App\Model\Article',
            'receiver' => '-1'
        ]);

        $response = $this->get('/api/admin/dashboard');
        
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'userInfo' => [],
                    'articleInfo' => [
                        ['count' => 3, 'topic_id' => 2],
                        ['count' => 9, 'topic_id' => 3],
                        ['count' => 10, 'topic_id' => 5],
                    ],
                    'illegalInfo' => [
                        ['count' => 1, 'type' => 'App\Model\Comment'],
                        ['count' => 10, 'type' => 'App\Model\Article'],
                    ]
                ]
            ]);
    }
}
 