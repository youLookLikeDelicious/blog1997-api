<?php

namespace Tests\Feature\Home;

use Tests\TestCase;
use App\Model\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveMessageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @group feature
     * 
     * 测试 获取博客留言
     * @return void
     */
    public function test_get_leave_message_when_message_is_empty()
    {
        $response = $this->json('GET', '/api/leave-message');

        $response->assertStatus(200);

        $comments = json_decode($response->getContent())->data->records;

        $this->assertEquals(count($comments), 0);
    }

    /**
     * @group feature
     * 
     * 测试当留言不为空的情况
     */
    public function test_get_leave_message_when_message_is_not_empty ()
    {
        $this->makeUser();

        $comments = factory(Comment::class, 10)->states('Blog1997')->create();

        $response = $this->json('GET', '/api/leave-message');

        $response->assertStatus(200);

        $responseComments = json_decode($response->getContent())->data->records;

        $this->assertEquals(count($comments), 10);

        foreach($responseComments as $key => $responseComment) {
            $this->assertEquals($responseComment->id, $comments[$key]->id);
        }
    }
}
