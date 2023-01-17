<?php

namespace Tests\Feature\Home;

use Tests\TestCase;
use App\Models\Tag;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class ArticleTest extends TestCase
{
    /**
     * 开启导致全文索引不可用
     */
    use RefreshDatabase;

    /**
     * A basic feature test example.
     * @group feature
     * 
     * @return void
     */
    public function test_get_article_by_id()
    {
        // 新建文章
        $this->makeUser('author');

        $article = Article::factory()->create([
            'user_id' => $this->user->id
        ]);

        $articleId = base64_encode($article->id);

        $response = $this->json('GET', "/api/article/{$articleId}");

        $response->assertStatus(200);
        $responseArticle = json_decode($response->getContent())->data->article;

        $this->assertEquals($article->id, base64_decode($responseArticle->identity));

        $this->assertEquals($article->content, $responseArticle->content);

        $this->assertDatabaseHas('sitemap_info', [
            'sitemap_url' => env('APP_URL') . '/article/' . base64_encode($article->id)
        ]);
    }

    /**
     * @group feature
     * 
     * 测试获取列表
     */
    public function test_get_list () {
        // 生成多个文章
        $this->makeUser('master');

        Article::factory()->count(20)
            ->has(Tag::factory())
            ->create([
                'user_id' => $this->user->id
            ]);
        
        $response = $this->json('GET', '/api/article/search');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('meta')->where('meta.total', 21)->etc()
            );

        /******************************************************************
         *  查询，未知的 topic id
         ******************************************************************/
        $response = $this->json('GET', '/api/article/search?tag_id=99');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'total' => 0
                ]
            ]);
    }

    /**
     * test get article list with illegal param
     * @group feature
     * @return void
     */
    public function test_with_illegal_tag_id()
    {
        $response = $this->json('GET', '/api/article?tag_id=1s2');

        $response->assertStatus(404);
    }

    /**
     * 测试搜索文章
     * @group feature
     *
     * @return void
     */
    public function test_search()
    {
        // 生成多个文章
        $this->makeUser('master');

        Article::factory()->count(19)->create([
            'user_id' => $this->user->id
        ]);

        Article::factory()->create([
            'user_id' => $this->user->id,
            'content' => '江畔何人初见月,江月何年初照人.'
        ]);

        $response = $this->json('get', '/api/article/search?keyword=江畔何人初见月');

        /**
         * 全文索引在transaction中不可用
         */
        
        $response->assertStatus(200);
    }

    /**
     * 测试获取评论
     * @group feature
     *
     * @return void
     */
    public function test_get_comments()
    {
        $this->makeUser();

        // create article
        $article = Article::factory()->create();

        // 为文章添加评论
        Comment::factory()->create([
            'able_id' => $article->id,
            'able_type'  => 'article'
        ]);

        $response = $this->post('/api/article/comments/' . base64_encode($article->id));

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('meta.total', 1)->etc()
            );
    }

    /**
     * 测试搜索标签
     * @group feature
     *
     * @return void
     */
    public function test_search_tag()
    {
        $this->makeUser();

        // 创建文章
        Article::factory()
            ->has(Tag::factory())
            ->create();

        // 创建标签
        $tag = Tag::factory()->create();

        // 通过标签搜索过滤文章
        $response = $this->get('/api/article/tags');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('meta.total', 1)->etc()
            );

        // 搜索空列表
        $response = $this->get('/api/article/tags?tag_id=' . $tag->id);
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('meta.total', 0)->etc()
            );
    }
}
