<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Model\Gallery;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

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
                'data' => [
                    'records' => []
                ]
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

        factory(Gallery::class, 20)->create();

        $response = $this->json('GET', '/api/admin/gallery');

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
            ]
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'url', 'created_at', 'updated_at'
                    ]
                ]
            ]);
        
        // 期望数据库中存在该记录
        $responseUrl = json_decode($response->getContent())->data;
        $gallery = Gallery::select('id', 'url')->where('url', $responseUrl[0]->url)->first();
        $this->assertNotNull($gallery);

        // 期望文件存在目录中
        $this->assertTrue(Storage::exists($responseUrl[0]->url));

        // 删除相册的图片
        $response = $this->delete('/api/admin/gallery/' . $gallery->id);
        $response->assertStatus(200);
        $this->assertTrue(!Storage::exists($responseUrl[0]->url));
    }
}
