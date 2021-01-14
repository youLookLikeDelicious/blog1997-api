<?php

namespace Tests\Feature\Admin;

use App\Model\Tag;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
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

        $tags = factory(Tag::class, 10)->create();

        $tags2 = factory(Tag::class, 3)->create([
            'parent_id' => $tags[0]->id
        ]);

        $response = $this->json('get', '/api/admin/tag');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'pagination' => [
                        'total' => 10
                    ]
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

        $response = $this->json('post', '/api/admin/tag', [
            'name' => '前端',
            'description' => '对标签的描述',
            'parent_id' => 0
        ]);
        
        $response->assertStatus(403);
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
        $tag = json_decode($response->getContent())->data;

        $this->makeUser();

        // 测试添加重复的标签
        $response = $this->json('post', '/api/admin/tag', [
            'name' => '前端',
            'description' => '对标签的描述',
            'parent_id' => -1
        ]);
        
        $response->assertStatus(400);

        // 再测试删除
        $response = $this->json('delete', '/api/admin/tag/' . $tag->id);
        $response->assertStatus(403);
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
        $tag = json_decode($response->getContent())->data;
        $this->assertFileExists(storage_path($tag->cover));
        
        // 测试删除，同时删除 标签 封面
        $response = $this->json('delete', '/api/admin/tag/' . $tag->id);
        $response->assertStatus(200);
        $this->assertFileNotExists(storage_path($tag->cover));
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

        $tag = factory(Tag::class)->create();
        $parentTag = factory(Tag::class)->create();

        $response = $this->json('put', '/api/admin/tag/' . $tag->id, [
            'name' => '前端',
            'description' => '对标签的描述-修改',
            'parent_id' => $parentTag->id
        ]);
        
        $response->assertStatus(200);
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
