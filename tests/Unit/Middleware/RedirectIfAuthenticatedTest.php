<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\RedirectIfAuthenticated as Middleware;
use App\Model\User;

class RedirectIfAuthenticatedTest extends TestCase
{
    /**
     * A basic unit test
     * @group unit
     *
     * @return void
     */
    public function test()
    {
        $user = factory(User::class)->make();

        Auth::login($user);

        $middleware = new Middleware();

        $request = new Request();
        
        $response = $middleware->handle($request, function () {
            return response()->success();
        });

        $this->assertContains('https://www.blog1997.com/home', $response->getContent());
    }

    /**
     * A basic unit test
     * @group unit
     *
     * @return void
     */
    public function test_without_authenticate()
    {
        $middleware = new Middleware();

        $request = new Request();
        
        $response = $middleware->handle($request, function () {
            return response()->success();
        });

        $this->assertNotContains('https://www.blog1997.com/home', $response->getContent());
    }
}
