<?php

namespace Tests\Feature;

use App\User;
use App\UserLocation;
use App\UserPersonal;
use Tests\TestCase;

class AuthTest extends TestCase
{

    private $currentUser;

    private function createUser()
    {
    	$this->currentUser = factory(User::class)->create();
        $user_personal     = factory(UserPersonal::class)->create(['user_id' => $this->currentUser->id]);
        $user_location     = factory(UserLocation::class)->create(['user_id' => $this->currentUser->id]);
    }

    private function actingAsUser()
    {
        $this->createUser();

        $this->actingAs($this->currentUser);
    }

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
        	'email' => $this->currentUser->email, 
        	'password' => 'password',
        	'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS']
        )->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if(!$result['success'])
	        $this->fail($result['msg']);
    }

    /** @test */
    public function form_signin_in_correct_email()
    {
    	$this->withoutExceptionHandling();

    	$this->createUser();

        $response = $this->post('/system/signIn', [
        	'email' => 'a@a', 
        	'password' => 'password',
        	'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS']
        )->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if($result['success'])
	        $this->fail('Succes sign in to panel with incorrect email');
    }

    /** @test */
    public function form_signin_in_correct_password()
    {
    	$this->withoutExceptionHandling();

    	$this->createUser();

        $response = $this->post('/system/signIn', [
        	'email' => $this->currentUser->email, 
        	'password' => 'xdxd',
        	'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS']
        )->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if($result['success'])
	        $this->fail('Succes sign in to panel with incorrect password');
    }

    /** @test */
    public function form_signin_in_correct_recaptcha()
    {
    	$this->withoutExceptionHandling();

    	$this->createUser();

        $response = $this->post('/system/signIn', [
        	'email' => $this->currentUser->email, 
        	'password' => 'password',
        	'grecaptcha' => 'XDXD']
        )->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if($result['success'])
	        $this->fail('Succes sign in to panel with incorrect recaptcha');
    }
}
