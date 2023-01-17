<?php

namespace Tests\Unit\Repository\Admin;

use App\Contract\Repository\Gallery;
use App\Models\Gallery as ModelGallery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $repository = app()->make(Gallery::class);

        $result = $repository->count();
        $this->assertEquals(0, $result);

        // 添加相册
        ModelGallery::insert( [['url' => 'url'], ['url' => 'url'], ['url' => 'url'], ['url' => 'url'], ['url' => 'url'], ['url' => 'url']]);
        $result = $repository->count();
        $this->assertEquals(6, $result);
    }
}
