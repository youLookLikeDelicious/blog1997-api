<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test()
    {
        $this->makeUser();
        $response = $this->json('get', '/api/admin/comment');
        $response->assertStatus(200);

        // make some data
        Comment::factory()->count(20)->suspended('no-verified')->create();
        $response = $this->json('get', '/api/admin/comment');
        $response->assertStatus(200);
        $count = json_decode($response->getContent())->meta->total;
        $this->assertEquals(20, $count);
    }

    /**
     * Test approve comment
     * @group feature
     *
     * @return void
     */
    public function test_approve()
    {
        $this->makeUser();
        $comment = Comment::factory()->suspended('no-verified')->create();
        $response = $this->json('post', '/api/admin/comment/approve/', ['ids' => [$comment->id]]);

        $response->assertStatus(200);
        $comment = Comment::select('verified')->find($comment->id);
        $this->assertEquals('yes', $comment->verified);
    }

    /**
     * Test reject comment
     * @group feature
     *
     * @return void
     */
    public function test_reject()
    {
        $this->makeUser();

        $comment = Comment::factory()->suspended('no-verified')->create();

        $response = $this->json('delete', '/api/admin/comment/reject' , ['ids' => [$comment->id]]);

        $response->assertStatus(200);

        $comment = Comment::select('verified')->find($comment->id);
        
        $this->assertNull($comment);
    }
}
