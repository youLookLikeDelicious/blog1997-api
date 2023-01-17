<?php

namespace Tests\Traits\Feature\Comment;

use App\Models\Comment;
use App\Facades\CacheModel;

trait LeaveMessageTrait {
    /**
     * 测试留言及回复
     * @group feature
     */
    public function test_leave_message_when_user_login_with_correct_data ()
    {
        $this->initUserAndArticleModel();

        // 给博客留言
        $response = $this->json('post', '/api/comment', []);

        $response->assertStatus(422);

        $response = $this->json('POST', '/api/comment', [
            'able_id' => 0,
            'able_type' => 'Blog1997',
            'content' => '<p style="color: rgb(22, 22, 22); font-size: 1.4rem;">我有一件礼物想呈现给你，那就是在孤独难耐的夜晚，依然会闪闪发光的 漫天繁星</p>',
            'level' => 1,
        ]);
        
        $response->assertStatus(200);

        // 博客留言回复
        $comment = json_decode($response->getContent());
        
        $response = $this->json('POST', '/api/comment', [
            'level' => 2,
            'able_type' => 'comment',
            'able_id' => $comment->data->id,
            'content' => 'leave message reply'
        ]);
        
        $response->assertStatus(200);
    }

    /**
     * 测试删除留言
     * 
     * @group feature
     * @return void
     */
    public function test_destroy_leave_message()
    {
        $this->makeUser();

        $rootComment = Comment::factory()->suspended('Blog1997')->create();

        $leaveMessage = Comment::factory()->suspended('comment')
            ->create([
                'able_id' => $rootComment->id,
                'root_id' => $rootComment->id,
                'level' => 2
            ]);

        $leaveMessage = Comment::factory()
            ->suspended('comment')
            ->create([
                'able_id' => $leaveMessage->id,
                'root_id' => $rootComment->id,
                'level' => 3
            ]);

        $response = $this->json('post', '/api/comment/' . $rootComment->id, ['_method' => 'delete']);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'rows' => 3
                ]
            ]);

        $count = Comment::selectRaw('count(id) as count')->first()->count;

        $this->assertEquals($count, 0);
    }

    /**
     * 测试删除留言的回复
     * 
     * @group feature
     * @return void
     */
    public function test_destroy_leave_message_reply()
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

        $leaveMessage3 = Comment::factory()
            ->suspended('comment')
            ->create([
                'able_id' => $leaveMessage2->id,
                'root_id' => $rootComment->id,
                'level' => 3
            ]);

        $response = $this->json('post', '/api/comment/' . $leaveMessage3->id, ['_method' => 'delete']);
        
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'rows' => 1
                ]
            ]);

        $count = Comment::selectRaw('count(id) as count')->first()->count;

        $this->assertEquals($count, 2);
    }

    /**
     * 测试删除留言的回复，并且回复有回复
     * 
     * @group feature
     * @return void
     */
    public function test_destroy_leave_message_reply_witch_have_reply()
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

        $leaveMessage3 = Comment::factory()
            ->suspended('comment')
            ->create([
                'able_id' => $leaveMessage2->id,
                'root_id' => $rootComment->id,
                'level' => 3
            ]);

        Comment::factory()
            ->suspended('comment')
            ->create([
                'able_id' => $leaveMessage3->id,
                'root_id' => $rootComment->id,
                'level' => 3
            ]);

        $response = $this->delete('/api/comment/'.$leaveMessage3->id);
        
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'rows' => 1
                ]
            ]);

        $count = Comment::selectRaw('count(id) as count')->first()->count;

        $this->assertEquals($count, 2);
    }
}