<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserLocation;
use App\Models\UserPersonal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class UserExtendTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      info | user

    /** @test */
    public function info_user()
    {
        $user = factory(User::class)->create();
        $user_info     = factory(UserInfo::class)->create(['user_id' => $user->id]);

        $this->assertEquals($user_info->user->id, $user->id);
    }

    //
    //      location | user

    /** @test */
    public function location_user()
    {
        $user = factory(User::class)->create();
        $user_location     = factory(UserLocation::class)->create(['user_id' => $user->id]);

        $this->assertEquals($user_location->user->id, $user->id);
    }

    //
    //      personal | user

    /** @test */
    public function personal_user()
    {
        $user = factory(User::class)->create();
        $user_personal     = factory(UserPersonal::class)->create(['user_id' => $user->id]);

        $this->assertEquals($user_personal->user->id, $user->id);
    }
}
