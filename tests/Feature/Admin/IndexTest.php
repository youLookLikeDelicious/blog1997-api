<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use App\Models\Article;
use App\Models\MessageBox;
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
        Topic::factory()->create([
            'id' => 2
        ]);
        Topic::factory()->create([
            'id' => 3
        ]);
        Topic::factory()->create([
            'id' => 5
        ]);
        // 模拟文章数据----------------------------------------------------------
        Article::factory()->count(3)->create([
            'topic_id' => 2
        ]);

        Article::factory()->count(9)->create([
            'topic_id' => 3
        ]);

        Article::factory()->count(10)->create([
            'topic_id' => 5
        ]);

        // 模拟用户数据--------------------------------------------------------
        User::factory()->count(12)->create();

        // 模拟举报信息--------------------------------------------------------
        MessageBox::factory()->create([
            'type' => 'comment',
            'receiver' => '-1'
        ]);

        MessageBox::factory()->count(10)->create([
            'type' => 'article',
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
                        ['count' => 10, 'type' => 'article'],
                        ['count' => 1, 'type' => 'comment'],
                    ]
                ]
            ]);
    }
}
 