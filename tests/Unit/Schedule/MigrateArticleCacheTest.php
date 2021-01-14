<?php

namespace Tests\Unit\Schedule;

use App\Facades\CacheModel;
use App\Model\Article;
use App\Schedule\MigrateArticleCache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MigrateArticleCacheTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     * @group unit
     *
     * @return void
     */
    public function test_without_cache_data()
    {
        $this->makeUser('master');
        $schedule = new MigrateArticleCache();

        $article = factory(Article::class)->create([
            'visited' => 0
        ]);

        $schedule();

        $article = Article::select('visited')
            ->find($article->id);
        
        $this->assertEquals($article->visited, 0);
    }

    /**
     * A basic unit test example.
     * @group unit
     *
     * @return void
     */
    public function test_with_cache_data()
    {
        $this->makeUser('master');

        $article = factory(Article::class)->create([
            'visited' => 0,
            'commented' => 0,
            'liked' => 0
        ]);

        CacheModel::incrementArticleCommented($article->id);
        CacheModel::incrementArticleVisited($article->id);
        CacheModel::incrementArticleLiked($article->id);

        $schedule = new MigrateArticleCache();

        $schedule();

        $article = Article::select('visited', 'commented', 'liked')
            ->find($article->id);
        
        $this->assertEquals($article->visited, 1);
        $this->assertEquals($article->liked, 1);
        $this->assertEquals($article->commented, 1);

        $this->assertEquals(0, CacheModel::getArticleCommented($article->id));
        $this->assertEquals(0, CacheModel::getArticleLiked($article->id));
        $this->assertEquals(0, CacheModel::getArticleVisited($article->id));
    }
}
