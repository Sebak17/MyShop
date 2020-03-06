<?php

namespace Tests\Unit\Models;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class BanTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      user

    /** @test */
    public function user()
    {
        $user = factory(User::class)->create();
        $ban  = factory(Ban::class)->create(['user_id' => $user->id]);

        $this->assertEquals($ban->user->id, $user->id);
    }
}
