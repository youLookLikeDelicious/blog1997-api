<?php

namespace Tests\Feature\Admin;

use App\Models\Tag;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_index_without_data()
    {
        $this->makeUser('master');

        $response = $this->get('/api/admin/tag');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_index_with_data()
    {
        $this->makeUser('master');

        $tags = Tag::factory()->count(10)->create();

        Tag::factory()->count(3)->create([
            'parent_id' => $tags[0]->id
        ]);

        $response = $this->json('get', '/api/admin/tag');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'total' => 10
                ]
            ]);
    }

    /**
     * 测试author权限上传顶级标签
     * @group feature
     *
     * @return void
     */
    public function test_store_top_tag_without_master_auth()
    {
        $this->makeUser();

        $this->json('post', '/api/admin/tag', [
            'name' => '前端',
            'description' => '对标签的描述',
            'parent_id' => 0
        ])->assertStatus(403);
    }

    /**
     * 测试author权限上传自定义标签
     * @group feature
     *
     * @return void
     */
    public function test_store_custom_tag_with_author_auth()
    {
        $this->makeUser();

        $response = $this->json('post', '/api/admin/tag', [
            'name' => '前端',
            'description' => '对标签的描述',
            'parent_id' => -1
        ]);
        
        
        $response->assertStatus(200);

        // 获取tag id
        $tag = Tag::orderBy('id', 'desc')->first();

        $this->makeUser();

        // 测试添加重复的标签
        $this->json('post', '/api/admin/tag', [
            'name' => '前端',
            'description' => '对标签的描述',
            'parent_id' => -1
        ])->assertStatus(400);

        // 再测试删除
        $this->json('delete', '/api/admin/tag/' . $tag->id)
            ->assertStatus(403);
    }

    /**
     * 测试使用master角色创建标签
     * @group feature
     *
     * @return void
     */
    public function test_create_with_master_auth()
    {
        $this->makeUser('master');

        $response = $this->json('post', '/api/admin/tag', [
            'name' => '前端',
            'description' => '对标签的描述',
            'cover' => UploadedFile::fake()->image('cover.jpg'),
            'parent_id' => 0
        ]);

        $response->assertStatus(200);

        // 获取添加的模型
        $tag = Tag::orderBy('id', 'desc')->first();
        $tagCover = strstr($tag->cover, 'image');
        $this->assertFileExists(storage_path($tagCover));
        
        // 测试删除，同时删除 标签 封面
        $this->json('delete', '/api/admin/tag/' . $tag->id)->assertStatus(200);
    }

    /**
     * 测试使用master角色创建
     * @group feature
     *
     * @return void
     */
    public function test_update_with_master_auth()
    {
        $this->makeUser('master');

        $tag = Tag::factory()->create();
        $parentTag = Tag::factory()->create();

        $this->json('put', '/api/admin/tag/' . $tag->id, [
            'name' => '前端',
            'description' => '对标签的描述-修改',
            'parent_id' => $parentTag->id
        ])->assertStatus(200);
    }

    /**
     * Test create method
     * @group feature
     * 
     * @return void
     */
    public function test_create()
    {
        $this->makeUser();

        $response = $this->get('/api/admin/tag/create');

        $response->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);

        // 添加一些一级标签
        Tag::insert([
            [
                'name' => 'tag1',
                'parent_id' => 0,
                'user_id' => $this->user->id,
                'created_at' => time(),
                'updated_at' => time()
            ],[
                'name' => 'tag2',
                'parent_id' => 0,
                'user_id' => $this->user->id,
                'created_at' => time(),
                'updated_at' => time()
            ],
        ]);

        $response = $this->get('/api/admin/tag/create');

        $response->assertStatus(200);
        $tags = json_decode($response->getContent())->data;
        $this->assertEquals(2, count($tags));
    }

    /**
     * Test show specify tag
     * @group feature
     *
     * @return void
     */
    public function test_show()
    {
        $this->makeUser();

        $response = $this->get('/api/admin/tag/99');
        $response->assertStatus(404);

        // 创建一条tag记录
        Tag::insert([
            'id' => 99,
            'name' => 'tag2',
            'parent_id' => 0,
            'user_id' => $this->user->id,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $response = $this->get('/api/admin/tag/99');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => 99
                ]
            ]);
    }
}
