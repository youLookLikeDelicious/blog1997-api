<?php

namespace Tests\Unit\Repository\Home;

use Exception;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Models\Article as Model;
use App\Repository\Home\Article;
use App\Models\Article as ModelArticle;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test for article repository.
     * @group unit
     *
     * @return void
     */
    public function test_find_by_id_witch_is_not_number()
    {
        $repository = app()->make(Article::class);

        try{
            $repository->find('sdf');
        } catch(Exception $e) {
            $this->assertTrue($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException);
        }

    }

    /**
     * A basic unit test for article repository.
     * @group unit
     *
     * @return void
     */
    public function test_get_all_by_visit_num()
    {
        $this->makeUser('master');

        ModelArticle::factory()->count(10)->create();

        $repository = app()->make(Article::class);

        $request = new Request(['order_by' => 'visit']);

        $result = $repository->all($request);

        for($i = 0, $len = 9; $i < $len; $i++) {
            $this->assertTrue($result[$i]['visited'] >= $result[$i + 1]['visited']);
        }
    }

    /**
     * A basic unit test for article repository.
     * @group unit
     *
     * @return void
     */
    public function test_get_all_by_comment_num()
    {
        $this->makeUser('master');

        ModelArticle::factory()->count(10)->create();

        $repository = app()->make(Article::class);

        $request = new Request(['order_by' => 'commented']);
        
        $result = $repository->all($request);

        for($i = 0, $len = 9; $i < $len; $i++) {
            $this->assertTrue($result[$i]['commented'] >= $result[$i + 1]['commented']);
        }
    }

    /**
     * A basic unit test for article repository.
     * @group unit
     *
     * @return void
     */
    public function test_get_zero_article_count()
    {
        $repository = app()->make(Article::class);

        $result = $repository->getArticleCount();

        $this->assertEquals($result, 0);
    }

    /**
     * A basic unit test for article repository.
     * @group unit
     *
     * @return void
     */
    public function test_get_twenty_article_count()
    {
        $this->makeUser('master');

        Model::factory()->count(50)->create();

        $repository = app()->make(Article::class);

        $result = $repository->getArticleCount();

        $this->assertEquals($result, 50);
    }
}
