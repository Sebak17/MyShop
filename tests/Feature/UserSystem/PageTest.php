<?php

namespace Tests\Feature\UserSystem;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers as Helper;
use Tests\TestCase;

class PageTest extends TestCase
{

    use RefreshDatabase;
    use Helper;

    /** @test */
    public function pages_not_logged_in_cannot_see()
    {
        $response = $this->post('/systemUser/changePassword')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function pages_authenticated_admins_cannot_see()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemUser/changePassword')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function pages_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changePassword')->assertOk();
    }
}
