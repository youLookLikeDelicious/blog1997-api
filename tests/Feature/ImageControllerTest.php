<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class ImageControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     * @group feature
     *
     * @return void
     */
    public function test()
    {
        $this->makeUser();
        $this->get('/');
        $response = $this->post('/api/admin/upload/image/gallery', [
            'upfile' => [
                UploadedFile::fake()->image('photo1.jpg')
            ]
        ]);

        $response->cookie(env('APP_NAME') . '_session', 'app');

        $_COOKIE[env('APP_NAME') . '_session'] = 'app';

        $url = json_decode($response->getContent())->data[0];

        $response = $this->get($url);

        $response->assertStatus(200);
    }
}
