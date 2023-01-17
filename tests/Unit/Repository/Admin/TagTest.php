<?php

namespace Tests\Unit\Repository\Admin;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Repository\Admin\Tag;
use App\Models\Tag as ModelTag;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends TestCase
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
        $repository = app()->make(Tag::class);

        // 创建几条数据
        $topicTag = ModelTag::factory()->create([ 'user_id' => 0 ]);

        $tags = ModelTag::factory()->count(5)->create([ 'user_id' => 0, 'parent_id' => $topicTag->id ]);

        // 获取tag
        $request = new Request(['name' => $tags[2]->name]);
        $result = $repository->all($request);
        $this->assertEquals($result->count(), 0);
    }
}
