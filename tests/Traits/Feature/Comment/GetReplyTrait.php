<?php

namespace Tests\Traits\Feature\Comment;

use App\Models\Comment;
use App\Facades\RedisCache;

trait GetReplyTrait {
    /**
     * @group feature
     * 测试获取回复功能
     */
    public function testGetReplyMethod () {
        $this->initUserAndArticleModel();
        // 添加 一级 评论
        $rootComment = Comment::factory()->create([
            'able_id' => $this->article->id
        ]);

        // 添加二级评论
        $comment = Comment::factory()->suspended('level-2', 'comment')->create([
            'able_id' => $rootComment->id,
            'root_id' => $rootComment->id
        ]);

        $commentList = [];

        for ($i = 1, $len = 8; $i <= $len; $i++) {
            $commentList[] = Comment::factory()->suspended('level-3', 'comment')->create([
                'able_id' => $comment->id,
                'root_id' => $rootComment->id
            ]);
        }

        $this->assertEquals(Comment::where('root_id', $rootComment->id)->orWhere('id', $rootComment->id)->count(), 10);

        $response = $this->json('GET', "/api/comment/reply/{$rootComment->id}/3");

        $response->assertStatus(200);

        $data = json_decode($response->getContent())->data;
        
        for ($i = 2, $len = 8; $i < $len; $i++) {
            $this->assertEquals($commentList[$i]->id, $data[$i - 2]->id);
        }
    }
}
