<?php

namespace Tests\Unit\Repository;

use Tests\TestCase;
use App\Models\Comment;
use App\Facades\CacheModel;
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

        $rootComment = Comment::factory()
            ->suspended('Blog1997')
            ->create();

        $leaveMessage2 = Comment::factory()
            ->suspended('comment')
            ->create([
                'able_id' => $rootComment->id,
                'root_id' => $rootComment->id,
                'level' => 2
            ]);

        $leaveMessage = Comment::factory()
            ->suspended('comment')
            ->create([
                'able_id' => $leaveMessage2->id,
                'root_id' => $rootComment->id,
                'level' => 3
            ]);

        $leaveMessage = Comment::factory()
            ->suspended('comment')
            ->create([
                'able_id' => $leaveMessage2->id,
                'root_id' => $rootComment->id,
                'level' => 3
            ]);

        // 为二级评论创建回复
        Comment::factory()
            ->suspended('comment')
            ->create([
                'able_id' => $leaveMessage2->id,
                'root_id' => $rootComment->id,
                'level' => 3
            ]);

        CacheModel::incrementCommentLiked($leaveMessage->id);
        CacheModel::incrementCommentLiked($rootComment->id);

        $comments = app()->make(\App\Contract\Repository\Comment::class)->getComment(0, 'Blog1997');

        $this->assertEquals($comments[0]['liked'], 1);
        $this->assertEquals($comments[0]['commented'], 4);
    }
}
