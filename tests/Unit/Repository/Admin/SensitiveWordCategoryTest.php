<?php

namespace Tests\Unit\Repository\Admin;

use App\Contract\Repository\SensitiveWordCategory;
use App\Models\SensitiveWordCategory as ModelSensitiveWordCategory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class SensitiveWordCategoryTest extends TestCase
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
        $repository = app()->make(SensitiveWordCategory::class);

        // 添加几条数据
        $categories = factory(ModelSensitiveWordCategory::class, 5)->create();
        
        $request = new Request(['name' => $categories[2]->name, ['rank' => $categories[2]->rank]]);
        $result = $repository->all($request);

        $this->assertEquals($categories[2]->name, $result[0]->name);
    }
}
