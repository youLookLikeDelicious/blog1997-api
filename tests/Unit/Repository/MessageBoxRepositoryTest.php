<?php

namespace Tests\Unit\Repository;

use App\Contract\Repository\MessageBox as RepositoryMessageBox;
use App\Model\MessageBox;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class MessageBoxRepositoryTest extends TestCase
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
        $this->makeUser();

        factory(MessageBox::class, 50)->create([
            'receiver' => $this->user->id,
            'have_read' => 'no'
        ]);
        factory(MessageBox::class, 49)->create([
            'have_read' => 'yes',
            'receiver' => $this->user->id
        ]);
        
        $repository = app()->make(RepositoryMessageBox::class);

        // 获取已读的通知
        $request = new Request(['have_read' => 'yes']);
        $result = $repository->getNotification($request);

        $this->assertEquals(49, $result['pagination']['total']);
        $this->assertEquals($result['counts']['total'], 99);

        // 获取未读的通知
        $request = new Request(['have_read' => 'no']);
        $result = $repository->getNotification($request);
        $this->assertEquals(50, $result['pagination']['total']);

        // 再次获得未读的通知
        $request = new Request(['have_read' => 'no']);
        $result = $repository->getNotification($request);
        $this->assertEquals(0, $result['pagination']['total']);
    }
}
