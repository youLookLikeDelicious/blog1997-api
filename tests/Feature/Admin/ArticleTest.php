<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Model\User;
use App\Model\Topic;
use App\Model\Article;
use App\Model\Gallery;
use App\Model\ArticleTag;
use App\Model\Tag;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 测试获取文章列表
     * @group feature
     * 
     * @return void
     */
    public function test_get_list()
    {
        $this->makeUser('master');

        // 创建专题
        $topics = factory(Topic::class, 5)->create([
            'user_id' => $this->user->id
        ]);

        factory(Article::class, 20)->create([
            'topic_id' => $topics[0]->id,
            'user_id' => $this->user->id
        ]);

        /*******************************************************
         * 测试获取专题下没有文章的情况
         *******************************************************/
        $response = $this->json('get', '/api/admin/article?topicId=12');

        $response->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);

        $response = $this->json('GET', '/api/admin/article');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'pagination' => [
                        'total' => 20
                    ]
                ]
            ])
            ->assertJsonStructure([
                'data' => [
                    'pagination' => [
                        'total', 'uri', 'currentPage', 'next', 'previous', 'first', 'last', 'pages'
                    ],
                    'records' => [
                        '*' => [
                            'id', 'title', 'is_origin', 'visited', 'commented'
                        ]
                    ],
                    'topics' => [
                        '*' => [
                            'id', 'name'
                        ]
                    ],
                    'currentTopicId'
                ]
            ]);
    }

    /**
     * 测试在没有数据的情况下 获取文章列表
     * @group feature
     * 
     * @return void
     */
    public function test_get_list_when_there_is_no_data()
    {
        $this->makeUser('master');

        $response = $this->get('/api/admin/article?topicId=9');

        $response->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);
    }

    /**
     * 测试上传文章
     * @group feature
     * 
     * @return void
     */
    public function test_create_article_without_param()
    {
        $this->makeUser('author');
        $response = $this->json('POST', '/api/admin/article', []);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error'
            ]);
    }

    /**
     * 测试上传文章
     * @group feature
     * 
     * @return void
     */
    public function test_create_article()
    {
        $this->makeUser('author');

        factory(Topic::class)->create(['id' => 1]);

        $response = $this->json('POST', '/api/admin/article', [
            'title' => 'title',
            'topic_id' => 1,
            'is_origin' => 'yes',
            'content' => '内容<img src="****" />',
            'user_id' => $this->user->id
        ]);

        // 没有标签，期望失败 422
        $response->assertStatus(400);

        $response = $this->json('POST', '/api/admin/article', [
            'title' => 'title',
            'topic_id' => 1,
            'is_origin' => 'yes',
            'content' => '内容<img src="****" />',
            'user_id' => $this->user->id,
            'tags' => [11, 12, 'test'],
            'is_markdown' => 'yes'
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'user_id' => $this->user->id,
                    'summary' => '内容',
                    'gallery_id' => 1
                ]
            ]);


        // 专题对应的文章数量+1
        $topicArticleCount = Topic::select('article_sum')->find(1)->article_sum;

        $this->assertEquals(1, $topicArticleCount);

        // 用户对应的文章数量 + 1
        $userTopicCount = User::select('article_sum')->find($this->user->id)->article_sum;
        $this->assertEquals(1, $userTopicCount);

        // 与标签多对多的数量
        $count = ArticleTag::selectRaw('count(tag_id) as count')->first()->count;
        $this->assertEquals('3', $count);
    }

    /**
     * 测试上传文章
     * @group feature
     * 
     * @return void
     */
    public function test_create_article_and_get_gallery()
    {
        $this->makeUser('author');

        factory(Topic::class)->create(['id' => 1]);

        $galleries = factory(Gallery::class, 12)->create();

        // 创建一个文章
        $article = factory(Article::class)->create([
            'gallery_id' => $galleries[0]->id
        ]);

        $response = $this->json('POST', '/api/admin/article', [
            'title' => 'title',
            'topic_id' => 1,
            'is_origin' => 'yes',
            'content' => '内容<img src="****" />',
            'is_markdown' => 'yes',
            'tags' => [1, 2, 'test']
        ]);


        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'user_id' => $this->user->id,
                    'summary' => '内容',
                    'gallery_id' => $galleries[0]->id + 1
                ]
            ]);

        // 专题对应的文章数量+1
        $topicArticleCount = Topic::select('article_sum')->find(1)->article_sum;

        $this->assertEquals(2, $topicArticleCount);

        // 用户对应的文章数量 + 1
        $userTopicCount = User::select('article_sum')->find($this->user->id)->article_sum;
        $this->assertEquals(2, $userTopicCount);
    }

    /**
     * 测试添加封面 
     * @group feature
     *
     * @return void
     */
    public function test_create_with_coverage()
    {
        $this->makeUser('author');

        factory(Topic::class)->create(['id' => 1]);

        $response = $this->json('POST', '/api/admin/article', [
            'title' => 'title',
            'topic_id' => 1,
            'is_origin' => 'yes',
            'content' => '内容<img src="****" />',
            'user_id' => $this->user->id,
            'tags' => [11, 12, 'test'],
            'is_markdown' => 'yes',
            'coverage' => UploadedFile::fake()->image('coverage.jpg')
        ]);

        $response->assertStatus(200);
    }

    /**
     * 测试更新文章
     * @group  feature
     * 
     * @return void
     */
    public function test_update()
    {
        $this->makeUser('author');

        factory(Topic::class)->create(['id' => 1]);

        $article = factory(Article::class)->create([
            'user_id' => $this->user->id,
            'topic_id' => 1,
            'is_draft' => 'no'
        ]);

        // 修改文章
        $response = $this->json('POST', "/api/admin/article/{$article->id}", [
            'title' => 'title',
            'topic_id' => 1,
            'is_origin' => 'yes',
            'topic' => 1,
            'content' => 'changed',
            'tags' => ['sdf'],
            '_method' => 'PUT'
        ]);

        $response->assertStatus(200);
        $content = Article::select('content')->find($article->id)->content;

        $this->assertEquals($content, 'changed');

        // 将文章保存为草稿
        $response = $this->json('put', "/api/admin/article/{$article->id}", [
            'is_draft' => 'yes'
        ]);
        
        $response->assertStatus(200);
        $message = json_decode($response->getContent())->message;
        $this->assertEquals('草稿保存成功', $message);
        // 获取草稿
        $draft = Article::draft($article->id)->first();
        $this->assertNotNull($draft);

        // 将草稿保存为文章
        $response = $this->json('put', "/api/admin/article/{$article->id}", [
            'is_draft' => 'no',
            'topic_id' => 1,
            'tags' => [1],
            'content' => 'new content'
        ]);
        
        $response->assertStatus(200);
        $message = json_decode($response->getContent())->message;
        $this->assertEquals('文章发布成功', $message);
        // 获取草稿
        $draft = Article::draft($article->id)->first();
        $this->assertNull($draft);
    }

    /**
     * 测试删除
     * @group  feature
     * 
     * @return void
     */
    public function test_delete_article()
    {
        $this->makeUser('author');

        $this->user->article_sum = 1;

        $this->user->save();

        $topic = factory(Topic::class)->create([
            'article_sum' => 1
        ]);

        $article = factory(Article::class)->create([
            'user_id' => $this->user->id,
            'topic_id' => $topic->id
        ]);

        // 删除之前 ，查看相关的数据
        $this->assertEquals(Topic::select('article_sum')->find($topic->id)->article_sum, 2);
        $this->assertEquals(User::select('article_sum')->find($this->user->id)->article_sum, 2);

        $response = $this->json('POST', '/api/admin/article/' . $article->id, [
            '_method' => 'delete'
        ]);

        $response->assertStatus(200);

        $this->assertEquals(Topic::select('article_sum')->find($topic->id)->article_sum, 1);
        $this->assertEquals(User::select('article_sum')->find($this->user->id)->article_sum, 1);

        // 期望回收站中存在 被删除的文章
        $response = $this->get('/api/admin/article?type=deleted');
        $response->assertStatus(200);
        $result = json_decode($response->getContent());
        $this->assertEquals($article->id, $result->data->records[0]->id);
        // 删除回收站中的文章
        $response = $this->json('POST', '/api/admin/article/' . $article->id, [
            '_method' => 'delete'
        ]);
        $response->assertStatus(200);
    }



    /**
     * 测试 获取一篇文章的详情
     * 
     * @group feature
     * @return void
     */
    public function test_get_article()
    {
        $this->makeUser('master');

        $article = factory(Article::class)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->json('GET', '/api/admin/article/' . $article->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'content' => $article->content
                ]
            ]);
    }

    /**
     * Test get create form data
     * @group feature
     *
     * @return void
     */
    public function test_get_create_form_data()
    {
        $this->makeUser();

        // 获取空的数据
        $this->get('/api/admin/article/create')->assertStatus(200);

        // 创建专题和标签
        factory(Topic::class, 2)->create([
            'user_id' => $this->user->id
        ]);
        factory(Tag::class, 2);

        $response = $this->get('/api/admin/article/create');
        $response->assertStatus(200);
    }
}
