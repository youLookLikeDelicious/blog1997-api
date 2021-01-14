<?php

namespace Tests;

use Tests\Traits\TestTrait;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, TestTrait;

    protected $originalExceptionHandler = false;

    protected function setUp () :void
    {
        parent::setUp();
        
        Redis::command('flushall');
        
        Cache::flush();
    }

    /**
     * 移除site map
     * @after
     * @return void
     */
    public function unlinkSiteMap()
    {
        $fileList = scandir(base_path('tests'));

        foreach($fileList as $file) {
            if (strpos($file, 'sitemap') === 0) {
                unlink(base_path('tests/' . $file));
            }
        }
    }
}
