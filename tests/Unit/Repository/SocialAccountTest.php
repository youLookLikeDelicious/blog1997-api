<?php

namespace Tests\Unit\Repository;

use App\Repository\SocialAccount;
use App\Model\SocialAccount as Model;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SocialAccountTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A social account repository
     * @group unit
     *
     * @return void
     */
    public function test_with_empty_data()
    {
        $repository = app()->make(SocialAccount::class);

        $this->assertNull($repository->find('123', 'github'));
    }

    /**
     * A social account repository
     * @group unit
     *
     * @return void
     */
    public function test_with_predefined_data()
    {
        factory(Model::class)
            ->create([
                'foreign_id' => 'social repository test',
                'type' => 2
            ]);

        $repository = app()->make(SocialAccount::class);

        $socialAccount = $repository->find('social repository test', 2);
        
        $this->assertEquals('social repository test', $socialAccount->foreign_id);
    }
}
