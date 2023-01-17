<?php

namespace Tests\Unit\Service;

use App\Service\CurlService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurlServiceTest extends TestCase
{
    /**
     * A basic unit test for curl service
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $response = CurlService::make('https://www.baidu.com', [
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36'
            ],
        ]);
        
        $this->assertTrue(strpos($response, '全球最大的中文搜索引擎、致力于让网民更便捷地获取信息，找到所求。百度超过千亿的中文网页数据库，可以瞬间找到相关的搜索结果。') >= 0);
    }
}
