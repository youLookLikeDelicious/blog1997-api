<?php

namespace Tests\Feature\Home;

use Tests\TestCase;
use App\Model\Article;
use App\Model\Comment;
use App\Model\ArticleBackUp;
use App\Model\IllegalComment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportIllegalInfoTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @group feature
     * 
     * @return void
     */
    public function test_report_illegal_article_witch_not_exists()
    {
        $this->makeUser();

        $article = factory(Article::class)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->json('POST', '/api/report-illegal-info', [
            'sender' => $this->user->id,
            'receiver' => $this->user->id,
            'content' => 'illegal info',
            'type' => 1,
            'reported_id' => $article->id + 1
        ]);
        
        $response->assertStatus(400);
    }

    /**
     * @group feature
     * 
     * @return void
     */
    public function test_report_illegal_article_witch_exists () {
        $this->makeUser();

        $article = factory(Article::class)->create();

        $response = $this->json('POST', '/api/report-illegal-info', [
            'sender' => $this->user->id,
            'receiver' => $this->user->id,
            'content' => 'illegal info',
            'type' => 1,
            'reported_id' => base64_encode($article->id)
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '举报成功，我们会及时处理，感谢您的配合'
            ]);
    }

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_report_illegal_comment_witch_not_exists()
    {
        $this->makeUser();

        $comment = factory(Comment::class)->states('Blog1997')->create();

        $response = $this->json('POST', '/api/report-illegal-info', [
            'sender' => $this->user->id,
            'receiver' => $this->user->id,
            'content' => 'illegal info',
            'type' => 2,
            'reported_id' => $comment->id + 1
        ]);

        $response->assertStatus(400);
    }

    /**
     * @group feature
     * 
     * @return void
     */
    public function test_report_illegal_comment_witch_exists () {
        $this->makeUser();

        $comment = factory(Comment::class)->states('Blog1997')->create();

        $response = $this->json('POST', '/api/report-illegal-info', [
            'sender' => $this->user->id,
            'receiver' => $this->user->id,
            'content' => 'illegal info',
            'type' => 2,
            'reported_id' => $comment->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '举报成功，我们会及时处理，感谢您的配合'
            ]);
    }

    /**
     * @group feature
     * 
     * @return void
     */
    public function test_report_illegal_comment_witch_has_been_processed () {
        $this->makeUser();

        $comment = factory(Comment::class)->states('Blog1997')->create();

        factory(IllegalComment::class)->create([
            'comment_id' => $comment->id
        ]);

        $response = $this->json('POST', '/api/report-illegal-info', [
            'sender' => $this->user->id,
            'receiver' => $this->user->id,
            'content' => 'illegal info',
            'type' => 2,
            'reported_id' => $comment->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '该记录已被处理,感谢您的配合'
            ]);
    }
}
