<?php

namespace Tests\Feature;

use App\User;
use App\UserLocation;
use App\UserPersonal;
use App\Admin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PanelAdminTest extends TestCase
{

	use RefreshDatabase;

	private $currentUser;
	private $currentAdmin;

	private function actingAsUser() {
        $this->currentUser = factory(User::class)->create();
        $user_personal = factory(UserPersonal::class)->create(['user_id' => $this->currentUser->id]);
        $user_location = factory(UserLocation::class)->create(['user_id' => $this->currentUser->id]);

        $this->actingAs($this->currentUser);
    }

    private function actingAsAdmin() {
        $this->currentAdmin = factory(Admin::class)->create();

        $this->actingAs($this->currentAdmin, 'admin');
    }

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
