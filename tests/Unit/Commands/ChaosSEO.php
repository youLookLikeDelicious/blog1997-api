<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChaosSEO extends TestCase
{
    /**
     * A basic unit test for generating site map commands.
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $this->artisan('sitemap:init')
            ->expectsOutput('sitemap initialize successful');

        $this->assertFileExists(base_path('tests/sitemap_2.xsl'));
        $this->assertFileExists(base_path('tests/sitemap.xsl'));
        $this->assertFileExists(base_path('tests/sitemap.xml'));
    }
}
