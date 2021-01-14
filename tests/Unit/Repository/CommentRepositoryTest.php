<?php

namespace Tests\Unit\Repository;

use App\Model\Comment;
use App\Facades\CacheModel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentRepository extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     * @group unit
     * @return void
     */
    public function test()
    {
        $this->makeUser();

        $rootComment = factory(Comment::class)
            ->states('Blog1997')
            ->create();

        $leaveMessage2 = factory(Comment::class)
            ->states('comment')
            ->create([
                'able_id' => $rootComment->id,
                'root_id' => $rootComment->id,
                'level' => 2
            ]);

        $leaveMessage = factory(Comment::class)
            ->states('comment')
            ->create([
                'able_id' => $leaveMessage2->id,
                'root_id' => $rootComment->id,
                'level' => 3
            ]);

        $leaveMessage = factory(Comment::class)
        ->states('comment')
        ->create([
            'able_id' => $leaveMessage2->id,
            'root_id' => $rootComment->id,
            'level' => 3
        ]);

        // 为二级评论创建回复
        factory(Comment::class)
            ->states('comment')
            ->create([
                'able_id' => $leaveMessage2->id,
                'root_id' => $rootComment->id,
                'level' => 3
            ]);

        CacheModel::incrementCommentLiked($leaveMessage->id);
        CacheModel::incrementCommentLiked($rootComment->id);

        $comments = app()->make(\App\Contract\Repository\Comment::class)->getComment(0, 'Blog1997');

        $this->assertEquals($comments['records'][0]['liked'], 1);
        $this->assertEquals($comments['records'][0]['commented'], 4);
    }
}
