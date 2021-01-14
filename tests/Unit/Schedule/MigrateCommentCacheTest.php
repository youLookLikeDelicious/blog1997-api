<?php

namespace Tests\Unit\Schedule;

use App\Facades\CacheModel;
use App\Model\Comment;
use App\Schedule\MigrateCommentCache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MigrateCommentCacheTest extends TestCase
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
        $schedule = new MigrateCommentCache();

        $comment = factory(Comment::class)->states('Blog1997')->create([
            'liked' => 0
        ]);

        $schedule();

        $article = Comment::select('liked')
            ->find($comment->id);
        
        $this->assertEquals($comment->liked, 0);
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

        $comment = factory(Comment::class)->states('Blog1997')->create([
            'commented' => 0,
            'liked' => 0
        ]);

        CacheModel::incrementCommentCommented($comment->id);
        CacheModel::incrementCommentLiked($comment->id);

        $schedule = new MigrateCommentCache();

        $schedule();

        $comment = Comment::select('commented', 'liked')
            ->find($comment->id);
        
        $this->assertEquals($comment->liked, 1);
        $this->assertEquals($comment->commented, 1);

        $this->assertEquals(0, CacheModel::getArticleCommented($comment->id));
        $this->assertEquals(0, CacheModel::getArticleLiked($comment->id));
        $this->assertEquals(0, CacheModel::getArticleVisited($comment->id));
    }
}
