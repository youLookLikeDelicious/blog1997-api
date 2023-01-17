<?php

namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\Topic;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test index
     * @group feature
     *
     * @return void
     */
    public function test_index_without_records()
    {
        $this->makeUser('master');

        $response = $this->get('/api/admin/topic');

        $response->assertStatus(200);
    }

    /**
     * test index
     * @group feature
     *
     * @return void
     */
    public function test_index()
    {
        $this->makeUser();

        Topic::factory(20)->create(['user_id' => $this->user->id]);

        $response = $this->get('/api/admin/topic');
        
        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'total' => 20
                ]
            ]);
    }
    
    /**
     * test store
     * @group feature
     *
     * @return void
     */
    public function test_store()
    {
        $this->makeUser('master');

        $response = $this->post('/api/admin/topic', [
            'name' => 'topic-name'
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'message' => '专题创建成功'
            ]);
    }

    /**
     * test update
     * @group feature
     *
     * @return void
     */
    public function test_update()
    {
        $this->makeUser('master');

        $topic = Topic::factory()->create([ 'user_id' => $this->user->id ]);

        $response = $this->put('/api/admin/topic/' . $topic->id, [
            'name' => 'topic-name'
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'message' => '专题修改成功'
            ]);
    }

    /**
     * test store
     * @group feature
     *
     * @return void
     */
    public function test_destroy()
    {
        $this->makeUser('master');

        $topic = Topic::factory()->create([ 'user_id' => $this->user->id ]);

        Article::factory()->count(20)->create([
            'topic_id' => $topic->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->delete('/api/admin/topic/' . $topic->id );
        
        $response->assertStatus(200)
            ->assertJson([
                'message' => '专题删除成功'
            ]);

        $articleNum = Article::selectRaw('count(id) as count')
            ->where('topic_id', 0)
            ->get()[0]->count;

        $this->assertEquals(20, $articleNum);
    }
}
