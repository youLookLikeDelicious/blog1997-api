<?php

use App\Facades\CacheModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CacheModelTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * basic test 
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $len = 200;
        for($i = 0; $i < $len; $i++) {
            CacheModel::incrementArticleVisited(1);
        }
        
        $count = CacheModel::getArticleVisited(1);
        
        $this->assertEquals(200, $count);
    }
}