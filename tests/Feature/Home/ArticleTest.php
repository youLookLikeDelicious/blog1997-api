<?php

namespace Tests\Feature\Home;

use App\Model\SiteMap;
use Tests\TestCase;
use App\Model\Article;
use App\Model\Comment;
use App\Model\Tag;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $article = factory(Article::class)->create([
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

        $articles = factory(Article::class, 20)->create([
            'user_id' => $this->user->id
        ]);

        $tag = factory(Tag::class)->create();

        foreach($articles as $article) {
            $article->tags()->sync([$tag->id]);
        }

        $_GET['p']  =1;
        
        $response = $this->json('GET', '/api/article/search');

        $response->assertStatus(200);

        $responseArticles = json_decode($response->getContent())->data->articles;
        // dump(Article::selectRaw('to_base64(id) as id')::all());
        $this->assertEquals(10, count($responseArticles));

        /******************************************************************
         *  查询，未知的 topic id
         ******************************************************************/
        $response = $this->json('GET', '/api/article/search?tag_id=' . ($tag->id + 1));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'articles' => [],
                    'p' => 0,
                    'pages' => 0,
                    'articleNum' => 0
                ]
            ]);

        $responseArticles = json_decode($response->getContent())->data->articles;

        $this->assertEquals(0, count($responseArticles));
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

        factory(Article::class, 19)->create([
            'user_id' => $this->user->id
        ]);

        factory(Article::class)->create([
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
        $article = factory(Article::class)->create();

        // 为文章添加评论
        factory(Comment::class)->create([
            'able_id' => $article->id,
            'able_type'  => 'App\Model\Article'
        ]);

        $response = $this->post('/api/article/comments/' . base64_encode($article->id));

        $result = json_decode($response->getContent())->data;

        $response->assertStatus(200);

        $this->assertNotEmpty($result->records[0]);
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
        $article = factory(Article::class)->create();

        // 创建标签
        $tags = factory(Tag::class, 2)->create();

        $article->tags()->sync($tags[0]->id);

        // 通过标签搜索过滤文章
        $response = $this->get('/api/article/tags');

        $response->assertStatus(200);

        $articles = json_decode($response->getContent())->data->articles;

        $this->assertNotEmpty($articles);

        // 搜索空列表
        $response = $this->get('/api/article/tags?tag_id=' . $tags[1]->id);

        $response->assertStatus(200);

        $articles = json_decode($response->getContent())->data->articles;

        $this->assertEmpty($articles);
    }
}
