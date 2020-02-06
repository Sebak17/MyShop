<?php

namespace Tests\Feature\UserAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers as Helper;
use Tests\TestCase;

class PageTest extends TestCase
{

    use RefreshDatabase;
    use Helper;

    //
    //        SIGN IN PAGE
    //

    /** @test */
    public function page_signin_not_logged_in_users_can_see()
    {
        $response = $this->get('/logowanie')->assertOk();
    }

    /** @test */
    public function page_signin_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/logowanie')->assertRedirect('/');
    }

    /** @test */
    public function page_signin_authenticated_admins_cannot_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/logowanie')->assertRedirect('/');
    }

    //
    //        SIGN UP PAGE
    //

    /** @test */
    public function page_signup_not_logged_in_users_can_see()
    {
        $response = $this->get('/rejestracja')->assertOk();
    }

    /** @test */
    public function page_signup_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/rejestracja')->assertRedirect('/');
    }

    /** @test */
    public function page_signup_authenticated_admins_cannot_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/rejestracja')->assertRedirect('/');
    }

    //
    //        SIGN UP PAGE
    //

    /** @test */
    public function page_logout_not_logged_in_users_cannot_see()
    {
        $response = $this->get('/wyloguj')->assertRedirect('/');
    }

    /** @test */
    public function page_logout_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->get('/wyloguj')->assertRedirect('/');
    }

    /** @test */
    public function page_logout_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/wyloguj')->assertRedirect('/');
    }

}
