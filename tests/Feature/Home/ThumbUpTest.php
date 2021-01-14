<?php

namespace Tests\Feature\Home;

use App\Contract\Repository\Article as RepositoryArticle;
use App\Model\Article;
use App\Model\MessageBox;
use App\Model\ThumbUp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThumbUpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 测试非法的参数
     * @group feature
     * 
     * @return void
     */
    public function test_when_user_logout()
    {
        $response = $this->json('POST', '/api/thumb-up');

        $response->assertStatus(401);
    }

    /**
     * 测试非法的参数
     * @group feature
     * 
     * @return void
     */
    public function test_without_params()
    {
        $this->makeUser();
        
        $response = $this->json('POST', '/api/thumb-up');

        $response->assertStatus(400)
            ->assertJsonStructure([ 
                'message' => [ 'id', 'category']
            ]);
    }

    /**
     * 测试点赞文章
     * @group feature
     * 
     * @return void
     */
    public function test_thumb_up_article()
    {
        $this->makeUser();
        
        $article = factory(Article::class)->create([ 'user_id' => $this->user->id ]);

        for ($i = 0, $len = 10; $i < $len; $i++) {
            $response = $this->json('POST', '/api/thumb-up', [
                'id' => $article->id,
                'category' => 'article'
            ]);
        }
        
        $response->assertStatus(200)
            ->assertJson([
                'message' => '点赞成功'
            ]);

        // 断言文章的点赞数量
        $articleLiked = app()->make(RepositoryArticle::class)->find(base64_encode($article->id))['article']['liked'];
        $this->assertEquals($articleLiked, $article->liked + $len);

        // 获取点赞的记录
        $thumbUp = ThumbUp::where('able_type', 'App\Model\Article')
                ->where('able_id', $article->id)
                ->first();
                
        // 断言点赞的通知
        $notification = MessageBox::where('type', ThumbUp::class)
                ->where('reported_id', $thumbUp->id)
                ->where('receiver', $this->user->id)
                ->first();

        // 因为自己给自己点赞,不会发送通知
        $this->assertNull($notification);

        // 模拟重新登陆
        $this->makeUser();
        $response = $this->json('POST', '/api/thumb-up', [
            'id' => $article->id,
            'category' => 'article'
        ]);
        
        // 断言点赞的通知
        $notification = MessageBox::where('type', ThumbUp::class)
            ->where('receiver', $article->user->id)
            ->first();
                
        $this->assertNotNull($notification);
    }

    /**
     * 测试非法的参数
     * @group feature
     * 
     * @return void
     */
    public function test_thumb_up_article_witch_not_exists()
    {
        $this->makeUser();
        
        $article = factory(Article::class)->create();

        for ($i = 0, $len = 60; $i < $len; $i++) {
            $response = $this->json('POST', '/api/thumb-up', [
                'id' => $article->id + 1,
                'category' => 'article'
            ]);
        }
        
        $response->assertStatus(404);
    }
}
