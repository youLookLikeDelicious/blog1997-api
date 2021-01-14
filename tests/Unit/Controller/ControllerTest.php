<?php

namespace Tests\Unit\Controller;

use App\Http\Controllers\Controller;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;

class ControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test for base controller.
     * @group unit
     *
     * @return void
     */
    public function test_rollback_with_redis()
    {
        $controller = new Controller;

        $controller->setCacheDriver('redis');

        $controller->beginTransition();

        DB::insert('insert into xy_sensitive_word (word, category_id) values (?, ?)', ['word', 1]);

        Redis::set('key', '1');
        
        $controller->rollBack();

        $count = DB::select('select count(id) as count from xy_sensitive_word')[0]->count;

        $this->assertNull(Redis::get('key'));

        $this->assertEquals(0, $count);
    }

    /**
     * A basic unit test for base controller.
     * @group unit
     *
     * @return void
     */
    public function test_commit_with_redis()
    {
        $controller = new Controller;

        $controller->setCacheDriver('redis');

        $controller->beginTransition();

        DB::insert('insert into xy_sensitive_word (word, category_id) values (?, ?)', ['word', 1]);

        Redis::set('key', '1');

        $controller->commit();

        $count = DB::select('select count(id) as count from xy_sensitive_word')[0]->count;

        $this->assertEquals('1', Redis::get('key'));

        $this->assertEquals(1, $count);
    }

    /**
     * A basic unit test for base controller.
     * @group unit
     *
     * @return void
     */
    public function test_rollback_without_redis()
    {
        $controller = new Controller;

        $controller->setCacheDriver('mongo');
        
        $controller->beginTransition();


        DB::insert('insert into xy_sensitive_word (word, category_id) values (?, ?)', ['word', 1]);

        Redis::set('key', '1');
        
        $controller->rollBack();

        $count = DB::select('select count(id) as count from xy_sensitive_word')[0]->count;

        $this->assertEquals('1', Redis::get('key'));

        $this->assertEquals(0, $count);
    }

    /**
     * A basic unit test for base controller.
     * @group unit
     *
     * @return void
     */
    public function test_commit_without_redis()
    {
        $controller = new Controller;

        $controller->beginTransition();

        DB::insert('insert into xy_sensitive_word (word, category_id) values (?, ?)', ['word', 1]);

        Redis::set('key', '1');

        $controller->commit();

        $count = DB::select('select count(id) as count from xy_sensitive_word')[0]->count;

        $this->assertEquals('1', Redis::get('key'));

        $this->assertEquals(1, $count);
    }
}
