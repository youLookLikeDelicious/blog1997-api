<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Model\Article;
use App\Model\MessageBox;
use App\Model\ArticleBackUp;
use App\Model\Comment;
use App\Model\IllegalComment;
use App\Model\ThumbUp;
use App\Model\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageBoxControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_index()
    {
        $this->makeUser('master');

        $response = $this->json('get', '/api/admin/illegal-info');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_index_with_predefined_data()
    {
        $this->makeUser('master');

        factory(MessageBox::class, 20)->create([
            'receiver' => -1
        ]);

        $response = $this->json('get', '/api/admin/illegal-info');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'pagination' => [
                        'total' => 20
                    ]
                ]
            ]);
    }

    /**
     * 测试批准非法的文章
     * @group feature
     *
     * @return void
     */
    public function test_approve_article()
    {
        $this->makeUser('master');

        $article = factory(Article::class)->create([
            'user_id' => $this->user->id
        ]);

        $messageBox = factory(MessageBox::class)->create([
            'reported_id' => $article->id,
            'type' => 'App\Model\Article'
        ]);

        $response = $this->json('post', '/api/admin/illegal-info/approve/' . $messageBox->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '审批成功'
            ]);
        
        // 期望文章被移到备份表中
        $this->assertNull(Article::find($article->id));

        $this->assertNotNull(ArticleBackUp::find($article->id));
    }

    /**
     * 测试 确认非法的评论
     * 
     * @group feature
     * @return void
     */
    public function test_approve_comment()
    {
        $this->makeUser('master');

        $comment = factory(Comment::class)->create([
            'user_id' => $this->user->id
        ]);

        $messageBox = factory(MessageBox::class)->create([
            'reported_id' => $comment->id,
            'type' => 'App\Model\Comment'
        ]);

        $response = $this->post('/api/admin/illegal-info/approve/' . $messageBox->id);

        $response->assertStatus(200);

        $illegalContent = Comment::select('content')->find($comment->id)->content;

        $this->assertEquals($illegalContent, '该评论涉嫌违规,已被删除');

        // 查看备份的非法评论
        $backUpContent = IllegalComment::select('content')->where('comment_id', $comment->id)->first()->content;

        $this->assertEquals($backUpContent, $comment->content);
    }

    /**
     * 测试 忽略举报
     * 
     * @group feature
     */
    public function test_ignore()
    {
        $this->makeUser('master');

        $comment = factory(Comment::class)->create([
            'user_id' => $this->user->id
        ]);

        $messageBox = factory(MessageBox::class)->create([
            'reported_id' => $comment->id,
            'type' => 2
        ]);

        $response = $this->post('/api/admin/illegal-info/ignore/' . $messageBox->id);

        $response->assertStatus(200);
    }

    /**
     * 测试获取通知
     * @group feature
     *
     * @return void
     */
    public function test_get_notification()
    {
        $this->makeUser();

        // 获取通知
        $response = $this->get('/api/admin/notification');
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'pagination' => [
                        'total' => 0
                    ]
                ]
            ]);

        // 获取通知相关的评论
        $this->get('/api/admin/notification/commentable-comments/999')->assertStatus(404);

        // 生成一条用户记录
        User::insert([
            'id' => 999,
            'name' => 999,
            'email' => '999@qq.com'
        ]);

        $user = User::find(999);
        $article = factory(Article::class)->create([
            'user_id' => $user->id
        ]);

        // 点赞文章
        ThumbUp::insert([
            'id' => 999,
            'user_id' => 999,
            'able_id' => $article->id,
            'able_type' => 'App\Model\Article',
            'to' => $this->user->id
        ]);

         // 评论文章
        Comment::insert([
            'id' => 999,
            'content' => 'hello world',
            'able_id' => $article->id,
            'user_id' => 999,
            'root_id' => 999,
            'able_type' => 'App\Model\Article'
        ]);
        // 生成两条通知
        MessageBox::insert([[
                'id' => 999,
                'sender' => 999,
                'receiver' => $this->user->id,
                'type' => 'App\Model\Comment',
                'content' => '评论了您的文章',
                'reported_id' => 999
            ], [
                'id' => 998,
                'sender' => 999,
                'receiver' => $this->user->id,
                'type' => 'App\Model\ThumbUp',
                'content' => '点赞了您的文章',
                'reported_id' => 999
            ]
        ]);

        // 获取通知
        $response = $this->get('/api/admin/notification');
        $response->assertStatus(200);
        $result = json_decode($response->getContent())->data;
        $this->assertEquals(2, count($result->records));

        // 获取通知相关的评论内容
        $response = $this->get('/api/admin/notification/commentable-comments/999');
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'pagination' => [
                        'total' => 1
                    ]
                ]
            ]);
    }
}
