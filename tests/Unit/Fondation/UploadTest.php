<?php

namespace Tests\Unit\Fondation;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use App\Facades\Upload as UploadFacades;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class Upload extends TestCase
{
    /**
     * A basic unit test example.
     * @group unit
     *
     * @return void
     */
    public function test_upload_img()
    {
        $files = UploadFacades::uploadImage(
            [UploadedFile::fake()->image('photo.jpg')],
            'article'
        );

        $path = $files->getFileList()[0];
        $path = trim($path, ':///');
        $this->assertTrue(Storage::exists($path));

        $webpPath = str_replace('jpg', 'webp', $path);
        $this->assertTrue(Storage::exists($webpPath));
    }

    /**
     * A basic unit test example.
     * @group unit
     *
     * @return void
     */
    public function test_upload_img_with_size()
    {
        $files = UploadFacades::uploadImage(
            [UploadedFile::fake()->image('photo.jpg')],
            'article',
            '100', '200'
        );

        $list = $files->getFileList();
        $this->assertTrue(Storage::exists(trim($list[0], ':///')));

        $files = UploadFacades::uploadImage(
            [UploadedFile::fake()->image('photo.jpg')],
            'article',
            '100'
        );

        $list = $files->getFileList();
        $this->assertTrue(Storage::exists(trim($list[0], ':///')));

        $files = UploadFacades::uploadImage(
            [UploadedFile::fake()->image('photo.jpg')],
            'article',
            0, 100
        );

        $list = $files->getFileList();
        $this->assertTrue(Storage::exists(trim($list[0], ':///')));
    }
}
