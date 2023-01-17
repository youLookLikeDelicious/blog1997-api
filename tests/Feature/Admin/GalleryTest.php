<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Gallery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_get_empty_data()
    {
        $this->makeUser('master');

        $response = $this->json('GET', '/api/admin/gallery');

        $response->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);
    }

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_get()
    {
        $this->makeUser('master');

        Gallery::factory()->count(20)->create();

        $response = $this->json('GET', '/api/admin/gallery');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'total' => 20
                ]
            ]);
    }

    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test_store_and_destroy()
    {
        $this->makeUser('master');

        // 上传新的图片
        $response = $this->json('post', '/api/admin/gallery', [
            'upfile' => [
                UploadedFile::fake()->image('photo1.jpg')
            ],
            'album'  => 'aa'            
        ]);

        $response->assertStatus(200);

        $gallery = Gallery::orderBy('id', 'desc')->first();

        // 期望文件存在目录中
        $filePath = strstr($gallery->url, 'image');
        $this->assertTrue(Storage::exists($filePath));

        // 删除相册的图片
        $response = $this->delete('/api/admin/gallery/' . $gallery->id);
        $response->assertStatus(200);
        $gallery->refresh();
        $this->assertNotNull($gallery->deleted_at);
    }
}
