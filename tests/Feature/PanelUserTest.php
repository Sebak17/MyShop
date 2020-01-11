<?php

namespace Tests\Feature;

use App\User;
use App\UserPersonal;
use App\UserLocation;
use Tests\Helpers as Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PanelUserTest extends TestCase
{

    use RefreshDatabase;
    use Helper;

    /** @test */
    public function dashboard_only_logged_in_users_can_see()
    {
        $response = $this->get('/panel')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function dashboard_only_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->get('/panel')->assertOk();
    }



    /** @test */
    public function orders_only_logged_in_users_can_see()
    {
        $response = $this->get('/panel/zamowienia')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function orders_only_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->get('/panel/zamowienia')->assertOk();
    }


    /** @test */
    public function settings_only_logged_in_users_can_see()
    {
        $response = $this->get('/panel/ustawienia')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function settings_only_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->get('/panel/ustawienia')->assertOk();
    }


    /** @test */
    public function favorites_only_logged_in_users_can_see()
    {
        $response = $this->get('/ulubione')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function favorites_only_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->get('/ulubione')->assertOk();
    }


    /** @test */
    public function shoppingCart_only_logged_in_users_can_see()
    {
        $response = $this->get('/koszyk')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function shoppingCart_only_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->get('/koszyk')->assertOk();
    }
}
