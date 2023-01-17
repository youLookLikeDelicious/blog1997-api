<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\SensitiveWordCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SensitiveWordCategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 测试Index
     * @group feature
     *
     * @return void
     */
    public function test_index()
    {
        $this->makeUser('master');

        factory(SensitiveWordCategory::class, 20)->create();

        $response = $this->json('GET', '/api/admin/sensitive-word-category');

        $response->assertStatus(200);
    }

    /**
     * 测试 store
     * @group feature
     *
     * @return void
     */
    public function test_store()
    {
        $this->makeUser('master');

        $this->json('POST', '/api/admin/sensitive-word-category', [
            'rank' => 1,
            'name' => '恶意辱骂'
        ])->assertStatus(200);
    }

    /**
     * 测试 create
     * @group feature
     *
     * @return void
     */
    public function test_update()
    {
        $this->makeUser('master');

        $category = factory(SensitiveWordCategory::class)->create([
            'name' => 'name'
        ]);

        $this->json('POST', '/api/admin/sensitive-word-category/' . $category->id, [
            'rank' => 1,
            'name' => '恶意辱骂',
            '_method' => 'PUT'
        ])->assertStatus(200);
    }

    /**
     * 测试 destroy
     * @group feature
     *
     * @return void
     */
    public function test_destroy()
    {
        $this->makeUser('master');

        $category = factory(SensitiveWordCategory::class)->create([
            'name' => 'name'
        ]);

        $this->json('POST', '/api/admin/sensitive-word-category/' . $category->id , [
            '_method' => 'delete'
        ])->assertStatus(200);
    }
}
