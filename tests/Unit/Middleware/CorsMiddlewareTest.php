<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\CorsMiddleware as Middleware;

class CorsMiddleware extends TestCase
{
    /**
     * A basic unit test
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $_SERVER['HTTP_ORIGIN'] = 'https://www.blog1997.com';

        $middleware = new Middleware();

        $request = new Request();
        $response = $middleware->handle($request, function ($request) {
            return response()->success($request);
        });
        
        $this->assertEquals('success', $response->original['message']);
    }

    /**
     * A basic unit test
     * @group unit
     *
     * @return void
     */
    public function test_with_illegal_url()
    {
        $_SERVER['HTTP_ORIGIN'] = 'https://www.blog1999.com';

        $middleware = new Middleware();

        $request = new Request();
        $response = $middleware->handle($request, function () {
            return response()->success();
        });

        $this->assertEquals('success', $response->original['message']);
    }
}
