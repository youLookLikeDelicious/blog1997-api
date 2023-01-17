<?php

namespace Tests\Feature\Home;

use Tests\TestCase;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

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

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('meta.total', 0)->etc()
            );
    }

    /**
     * @group feature
     * 
     * 测试当留言不为空的情况
     */
    public function test_get_leave_message_when_message_is_not_empty ()
    {
        $this->makeUser();

        Comment::factory()->count(10)->suspended('Blog1997')->create();

        $response = $this->json('GET', '/api/leave-message');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('meta.total', 10)->etc()
            );
    }
}
