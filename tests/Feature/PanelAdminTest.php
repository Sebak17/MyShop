<?php

namespace Tests\Feature;

use App\User;
use App\UserLocation;
use App\UserPersonal;
use App\Admin;
use Tests\TestCase;
use Tests\Helpers as Helper;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PanelAdminTest extends TestCase
{

	use RefreshDatabase;
    use Helper;

    /** @test */
    public function pages_not_logged_in_admins_cannot_see()
    {
        $response = $this->get('/admin/panel')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function pages_authenticated_users_cannot_see()
    {
    	$this->actingAsUser();

        $response = $this->get('/admin/panel')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function pages_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/panel')->assertOk();
    }

}
