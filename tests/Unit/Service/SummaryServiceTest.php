<?php

namespace Tests\Unit\Service;

use App\Service\SummaryService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SummaryServiceTest extends TestCase
{
    /**
     * Test extract article summary.
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $service = new SummaryService();

        $str = $service->make('123123123123<!-- more -->');

        $this->assertEquals($str, '123123123123');
    }

    /**
     * Test extract article summary.
     * @group unit
     *
     * @return void
     */
    public function test_without_more_tag()
    {
        $service = new SummaryService();

        $str = $service->make('123123123123');

        $this->assertEquals($str, '123123123123');
    }

    /**
     * Test extract article summary.
     * @group unit
     *
     * @return void
     */
    public function test_with_pre_tag()
    {
        $service = new SummaryService();

        $str = $service->make('<pre> 内容<img src="src">');

        $this->assertEquals($str, '<pre> 内容...</pre>');
    }
}
