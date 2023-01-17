<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test upload image
     * @group  feature
     * @return void
     */
    public function testExample()
    {
        $this->makeUser('master');

        // Storage::fake('photos');

        $response = $this->post('/api/admin/upload/image/gallery', [
            'upfile' => [
                UploadedFile::fake()->image('photo1.jpg')
            ]
        ]);

        $response->assertStatus(200);

        // 获取返回的文件列表
        $urls = json_decode($response->getContent())->data;

        Storage::assertExists($urls);

        // 生成webp文件格式路径
        $urls = array_map(function ($url) {
            return str_replace('jpg', '', $url) . 'webp';
        }, $urls);

        Storage::assertExists($urls);
    }
}
