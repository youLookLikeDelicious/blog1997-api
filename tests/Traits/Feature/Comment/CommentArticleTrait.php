<?php

namespace Tests\Traits\Feature\Comment;

use App\Facades\CacheModel;

trait CommentArticleTrait {
        /**
     * @group feature
     * 测试 当用户登陆的时候，评论文章 & 回复评论
     *
     * @return void
     */
    public function test_comment_article_when_user_login_with_correct_data()
    {
        $this->initUserAndArticleModel();

        $this->assertEquals(CacheModel::getArticleCommented($this->article->id), 0);

        // 提交评论
        $response = $this->post('/api/comment', [
            'level' => 1,
            'able_id' => $this->article->id,
            'able_type' => 'article',
            'content' => '<p>www.blog1997.com <img src="http://img.baidu.com/hi/jx2/j_0041.gif"></img></p>'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'message' => '评论成功'
            ]);

        $this->assertDatabaseHas('article', [
            'commented' => 0,
            'id' => 1
        ]);
        
        // 断言redis中缓存的数据
        $this->assertEquals(CacheModel::getArticleCommented($this->article->id), 1);

        // 模拟回复的行为
        $comment = json_decode($response->getContent())->data;
        $response = $this->post('/api/comment', [
            "reply_to" => "1",
            "able_id" => $comment->id,
            "able_type" => "comment",
            "content" => "测试回复"
        ]);
        
        $response->assertStatus(200)
            ->assertJsonFragment([
                'message' => '评论成功'
            ]);

        // 期望数据库中生成相关数据
        $this->assertDatabaseHas('comment', [
            'content' => '测试回复'
        ]);

        // 断言redis中缓存的数据 2    
        $this->assertEquals(CacheModel::getArticleCommented($this->article->id), 2);
        
        // 测试删除一级评论
        $response = $this->json('post', '/api/comment/' . $comment->id, [
            '_method' => 'delete'
        ]);

        $response->assertStatus(200);

        // 断言redis中缓存的数据 2
        $this->assertEquals(CacheModel::getArticleCommented($this->article->id), 0);
    }
}