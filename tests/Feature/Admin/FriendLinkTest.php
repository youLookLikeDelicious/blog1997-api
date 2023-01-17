<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\FriendLink;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FriendLinkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test create friend link
     * @group feature
     * 
     * @return void
     */
    public function test_create_without_param()
    {
        $this->makeUser('master');
        
        $response = $this->json('POST', '/api/admin/friend-link', []);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'message' => [ 'name', 'url']
            ]);
    }

    /**
     * test create friend link
     * @group feature
     * 
     * @return void
     */
    public function test_create()
    {
        $this->makeUser('master');
        
        $response = $this->json('POST', '/api/admin/friend-link', [
            'name' => 'blog1997',
            'url' => '123',
        ]);

        $response->assertStatus(200);
    }

    /**
     * test create friend link
     * @group feature
     * 
     * @return void
     */
    public function test_update()
    {
        $this->makeUser('master');
        
        $friendLink = factory(FriendLink::class)->create();

        $response = $this->json('POST', "/api/admin/friend-link/{$friendLink->id}", [
            'name' => 'blog1997',
            'url' => '123',
            '_method' => 'PUT'
        ]);

        $response->assertStatus(200);

        $this->assertEquals(FriendLink::find($friendLink->id)->url, '123');
    }

    /**
     * test create friend link
     * @group feature
     * @return void
     */
    public function test_delete()
    {
        $this->makeUser('master');
        
        $friendLink = factory(FriendLink::class)->create();

        $response = $this->json('delete', "/api/admin/friend-link/{$friendLink->id}");

        $response->assertStatus(200);

        $this->assertEquals(FriendLink::all()->count(), 0);
    }

    /**
     * test create friend link
     * @group feature
     * @return void
     */
    public function test_index()
    {
        $this->makeUser('master');
        
        factory(FriendLink::class, 20)->create();
        
        $response = $this->json('GET', "/api/admin/friend-link");
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'url']
                ]
            ]);
    }
}
