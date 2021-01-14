<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Model\SensitiveWord;
use App\Model\SensitiveWordCategory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SensitiveWordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_index_with_empty_data()
    {
        $this->makeUser('master');

        $response = $this->get('/api/admin/sensitive-word');

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_index()
    {
        $this->makeUser('master');

        $category = factory(SensitiveWordCategory::class)->create();

        factory(SensitiveWord::class, 20)->create([
            'category_id' => $category->id
        ]);

        $response = $this->get('/api/admin/sensitive-word');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'pagination' => [
                        'total' => 20
                    ]
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

        $category = factory(SensitiveWordCategory::class)->create([
            'count' => 0
        ]);

        $response = $this->json('POST', '/api/admin/sensitive-word', [
            'word' => '敏感词',
            'category_id' => $category->id
        ]);
        
        $response->assertStatus(200);

        $count = SensitiveWordCategory::select('id', 'count')->find($category->id)->count;
        $this->assertEquals($count, 1);
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

        $category = factory(SensitiveWordCategory::class)->create([
            'count' => 0    
        ]);

        $word = factory(SensitiveWord::class)->create([
            'word' => '敏感词'
        ]);

        $response = $this->json('POST', '/api/admin/sensitive-word/' . $word->id, [
            'word' => '敏感词',
            'category_id' => $category->id,
            '_method' => 'PUT'
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'message' => [ 'word' ]
            ]);

        $response = $this->json('POST', '/api/admin/sensitive-word/' . $word->id, [
            'word' => '敏感词汇',
            'category_id' => $category->id,
            '_method' => 'PUT'
        ]);

        $response->assertStatus(200);
    }

    /**
     * test delete
     * @group feature
     *
     * @return void
     */
    public function test_delete()
    {
        $this->makeUser('master');

        $category = factory(SensitiveWordCategory::class)->create();

        $sensitiveWords =  factory(SensitiveWord::class, 20)->create([
            'category_id' => $category->id
        ]);
        
        $response = $this->json('delete', '/api/admin/sensitive-word/' . $sensitiveWords[0]->id);
        $response->assertStatus(200);

        $count = SensitiveWordCategory::select('count')->find($category->id)->count;

        $this->assertEquals($count, 19);
    }

    /**
     * Test batch delete
     * @group feature
     *
     * @return void
     */
    public function test_batch_delete()
    {
        $this->makeUser();

        $category = factory(SensitiveWordCategory::class)->create();

        $sensitiveWords =  factory(SensitiveWord::class, 20)->create([
            'category_id' => $category->id
        ]);
        
        $response = $this->json('delete', '/api/admin/sensitive-word/batch-delete' ,['ids' => $sensitiveWords->pluck('id')->all()]);
        $response->dump();
        $response->assertStatus(200);

        $count = SensitiveWordCategory::select('count')->find($category->id)->count;

        $this->assertEquals($count, 0);
    }

    /**
     * 测试批量导入
     * @group feature
     *
     * @return void
     */
    public function test_import()
    {
        $this->makeUser('master');

        Storage::fake('txt');

        $category = factory(SensitiveWordCategory::class)->create();

        $path = __DIR__ . '/Resource/SensitiveWord.txt';
        $file = new UploadedFile($path, 'foo.txt', 'text/plain', null, true);

        $response = $this->json('POST', '/api/admin/sensitive-word/import', [
            'category_id' => $category->id,
            'file' => $file
        ]);
        
        $response->assertStatus(200);

        $count = SensitiveWordCategory::select('count')->find($category->id)->count;

        $this->assertEquals($count, 2);
    }
}
