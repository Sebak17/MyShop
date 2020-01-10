<?php

namespace Tests\Feature;

use App\User;
use App\UserLocation;
use App\UserPersonal;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;

    private $currentUser;

    private function createUser()
    {
    	$this->currentUser = factory(User::class)->create();
        $user_personal     = factory(UserPersonal::class)->create(['user_id' => $this->currentUser->id]);
        $user_location     = factory(UserLocation::class)->create(['user_id' => $this->currentUser->id]);
    }

    private function actingAsUser()
    {
        if($this->currentUser == null)
            $this->createUser();

        $this->actingAs($this->currentUser);
    }


    //
    //              SIGN IN
    //

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
    public function form_signin_incorrect_email()
    {
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
    public function form_signin_incorrect_password()
    {
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
    public function form_signin_incorrect_recaptcha()
    {
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

    //
    //              SIGN UP
    //

    /** @test */
    public function form_signup_only_not_logged_in_users_can_see()
    {
        $response = $this->post('/system/signUp')->assertOk();
    }

    /** @test */
    public function form_signup_only_not_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->post('/system/signUp')->assertRedirect('/');
    }

    /** @test */
    public function form_signup_correct_data()
    {
        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $result = json_decode($response->getContent(), true);
        if(!$result['success'])
            $this->fail($result['msg']);

        $this->assertCount(1, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_email_exist()
    {

        $this->createUser();

        $response = $this->post('/system/signUp', [
            'email' => $this->currentUser->email, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(1, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_fname_empty()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_fname_incorrect()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => 'John3',
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_sname_empty()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_sname_incorrect()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => 'McCollins1',
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_phone_empty()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_phone_incorrect()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => '+123',

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_district_empty()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_district_incorrect()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => 'abc',
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_city_empty()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_city_incorrect()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => '??',
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_zipcode_empty()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_zipcode_incorrect()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11=111',
            'address'    => $this->faker->streetName,
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_address_empty()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_address_incorrect()
    {

        $response = $this->post('/system/signUp', [
            'email' => $this->faker->unique()->safeEmail, 
            'pass' => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName . "?",
        ]
        )->assertJsonStructure();

        $this->assertCount(0, User::all());
    }
}
