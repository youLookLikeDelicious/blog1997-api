<?php

namespace Tests\Unit\Fondation;

use App\Facades\Page as FacadesPage;
use Tests\TestCase;
use App\Model\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Page extends TestCase
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
        $this->makeUser('master');

        factory(Article::class, 50)->create();

        $articleQuery = Article::select('id');

        // 测试默认的页数 1
        $result = FacadesPage::paginate(clone $articleQuery, 2, 5);

        $this->assertEquals($result['pagination']['first'], 1);
        
        // 测试 p = 23
        $_GET['p'] = 23;
        $result = FacadesPage::paginate(clone $articleQuery, 2, 5);
        $this->assertEquals($result['pagination']['first'], 21);

        // 测试 p = 12
        $_GET['p'] = 12;
        $_SERVER['REQUEST_URI'] = 'www.blog1997.com?p=12';
        $result = FacadesPage::paginate(clone $articleQuery, 2, 5);
        $this->assertEquals($result['pagination']['first'], 10);
    }
}
