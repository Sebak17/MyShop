<?php

namespace Tests\Feature\UserAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class SignInTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;
    use Helper;

    /** @test */
    public function form_signin_only_not_logged_in_users_can_see()
    {
        $response = $this->post('/system/signIn')->assertOk();
    }

    /** @test */
    public function form_signin_only_not_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->post('/system/signIn')->assertRedirect('/');
    }

    /** @test */
    public function form_signin_correct_data()
    {
        $this->withoutExceptionHandling();

        $this->createUser();

        $response = $this->post('/system/signIn', [
            'email'      => $this->currentUser->email,
            'password'   => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS']
        )->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_signin_incorrect_email()
    {
        $this->createUser();

        $response = $this->post('/system/signIn', [
            'email'      => 'a@a',
            'password'   => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS']
        )->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Succes sign in to panel with incorrect email');
        }

    }

    /** @test */
    public function form_signin_incorrect_password()
    {
        $this->createUser();

        $response = $this->post('/system/signIn', [
            'email'      => $this->currentUser->email,
            'password'   => 'xdxd',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS']
        )->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Succes sign in to panel with incorrect password');
        }

    }

    /** @test */
    public function form_signin_incorrect_recaptcha()
    {
        $this->createUser();

        $response = $this->post('/system/signIn', [
            'email'      => $this->currentUser->email,
            'password'   => 'password',
            'grecaptcha' => 'XDXD']
        )->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Succes sign in to panel with incorrect recaptcha');
        }

    }

}
