<?php

namespace Tests\Feature\Home;

use Tests\TestCase;
use App\Models\Article;
use App\Models\MessageBox;
use App\Facades\CacheModel;
use App\Models\SensitiveWord;
use Tests\Traits\Feature\Comment\GetReplyTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\Feature\Comment\LeaveMessageTrait;
use Tests\Traits\Feature\Comment\CommentArticleTrait;

class CommentTest extends TestCase
{
    use RefreshDatabase, CommentArticleTrait, LeaveMessageTrait, GetReplyTrait;

    protected $article;

    /**
     * @group feature
     * 测试评论功能 | 模拟用户未登录的情况
     *
     * @return void
     */
    public function test_when_user_not_login()
    {
        $response = $this->json('POST', '/api/comment', []);

        $response->assertStatus(401);
    }

    /**
     * 测试过滤script标签和font-size css样式
     * @group feature
     */
    public function test_filter_data ()
    {
        $this->initUserAndArticleModel();

        factory(SensitiveWord::class)->create([
            'word' => 's.b'
        ]);

        $content = '<p style="font-size: 123rem">font-size s.b</p><script>alert("sdf")</script>';
        $response = $this->json('post', '/api/comment', $this->getPostData('article', $content));

        $response->assertStatus(200);

        $comment = json_decode($response->getContent())->data;
        
        $this->assertEquals($comment->content, '<p>font-size **</p>');
    }

    /**
     * Test Comment model observer
     * @group feature
     *
     * @return void
     */
    public function test_observer()
    {
        $this->initUserAndArticleModel();

        // 一级评论
        $response = $this->json('post', '/api/comment', $this->getPostData('article', 'test', $this->article->id));
        $rootComment = json_decode($response->getContent())->data;

        // 二级评论
        $response = $this->json('post', '/api/comment', $this->getPostData('comment', 'test', $rootComment->id));
        $articleCommented = CacheModel::getArticleCommented($this->article->id);

        $this->assertEquals(2, $articleCommented);

        // 三级评论
        $comment = json_decode($response->getContent())->data;
        $response = $this->json('post', '/api/comment', $this->getPostData('comment', 'test', $comment->id));
        $articleCommented = CacheModel::getArticleCommented($this->article->id);
        $this->assertEquals(3, $articleCommented);

        $rootCommentCommented = CacheModel::getCommentCommented($rootComment->id);
        $this->assertEquals(2, $rootCommentCommented);

        // 测试生成的通知 
        // 都是自己的评论，不进行通知
        $notifications = MessageBox::selectRaw('count(id) as count')->where('type', 'comment')->get()[0];
        $this->assertEquals(0, $notifications->count);
    }

    /**
     * Test notification
     * @group feature
     *
     * @return void
     */
    public function test_notification()
    {
        $this->initUserAndArticleModel();

        $this->makeUser();
        // 一级评论
        $response = $this->json('post', '/api/comment', $this->getPostData('article', 'test', $this->article->id));
        $rootComment = json_decode($response->getContent())->data;

        // 二级评论
        $this->makeUser();
        $response = $this->json('post', '/api/comment', $this->getPostData('comment', 'test', $rootComment->id));
        $articleCommented = CacheModel::getArticleCommented($this->article->id);
        $this->assertEquals(2, $articleCommented);

        // 三级评论
        $this->makeUser();
        $comment = json_decode($response->getContent())->data;
        $response = $this->json('post', '/api/comment', $this->getPostData('comment', 'test', $comment->id));
        $articleCommented = CacheModel::getArticleCommented($this->article->id);
        $this->assertEquals(3, $articleCommented);

        $rootCommentCommented = CacheModel::getCommentCommented($rootComment->id);
        $this->assertEquals(2, $rootCommentCommented);

        // 测试生成的通知 
        $notifications = MessageBox::selectRaw('count(id) as count')->where('type', 'comment')->get()[0];
        $this->assertEquals(6, $notifications->count);

        // 同一个用户再次发表评论
        $response = $this->json('post', '/api/comment', $this->getPostData('comment', 'test', $comment->id));
        // 测试生成的通知 
        $notifications = MessageBox::selectRaw('count(id) as count')->where('type', 'comment')->get()[0];
        $this->assertEquals(6, $notifications->count);
    }

    /**
     * 测试评论功能 数据不合规则的情况
     * @group feature
     *
     * @return void
     */
    public function test_when_user_login_with_incorrect_data()
    {
        $this->initUserAndArticleModel();

        // 测试able_type 不属于 article,comment,Blog1997 并且 内容超过2100长度的情况
        $response = $this->json('POST', '/api/comment', $this->getPostData('articles', str_repeat('comment content-----', 601)));

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'content' => ['content 字符串长度不能超过 2100.'],
                    'able_type' => ['able type 的值无效.']
                ]
            ]);

        // 测试内容为空标签的情况
        $response = $this->post('/api/comment', $this->getPostData('article', '<p></p>'));

        $response->assertJsonStructure([
            'errors' => ['content']
        ]);

        // 测试提交其的图片，不属于 百度域名下的情况
        $response = $this->post('/api/comment', $this->getPostData('article', '<img src="https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=3482128345,2137456532&fm=26&gp=0.jpg"></img>'));
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'content' => ['未识别的图片格式']
                ]
            ]);
    }

    /**
     * 生成提交评论的表单数据
     * 
     * @return Array
     */
    protected function getPostData($commentType = 'article', $content = '', $commentableId = 1)
    {
        if (!$content) {
            $content = '<p>www.blog1997.com <img src="http://img.baidu.com/hi/jx2/j_0041.gif"></img></p>';
        }
        return [
            'able_id' => base64_encode($commentableId),
            'able_type' => $commentType,
            'content' => $content
        ];
    }

    /**
     * 
     */
    public function initUserAndArticleModel()
    {
        $this->makeUser();

        $this->article = Article::factory()->create([
            'id' => 1,
            'user_id' => $this->user->id,
            'commented' => 0
        ]);
    }
}
