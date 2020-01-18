<?php

namespace Tests\Feature\UserAuth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

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
        $data = $this->getSignUpData();

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(1, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_email_exist()
    {

        $this->createUser();

        $data = $this->getSignUpData();
        $data['email'] = $this->currentUser->email;

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(1, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_email_empty()
    {
        $data = $this->getSignUpData();
        unset($data['email']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_password_empty()
    {
        $data = $this->getSignUpData();
        unset($data['pass']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_grecaptcha_empty()
    {
        $data = $this->getSignUpData();
        unset($data['grecaptcha']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_grecaptcha_incorrect()
    {
        $data = $this->getSignUpData();
        $data['grecaptcha'] = 'dsa798bdasas';

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_fname_empty()
    {
        $data = $this->getSignUpData();
        unset($data['firstname']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_fname_incorrect()
    {
        $data = $this->getSignUpData();
        $data['firstname'] = "John3";

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_sname_empty()
    {
        $data = $this->getSignUpData();
        unset($data['surname']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_sname_incorrect()
    {
        $data = $this->getSignUpData();
        $data['surname'] = "McCollins1";

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_phone_empty()
    {

        $data = $this->getSignUpData();
        unset($data['phone']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_phone_incorrect()
    {
        $data = $this->getSignUpData();
        $data['phone'] = "+123";

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_district_empty()
    {
        $data = $this->getSignUpData();
        unset($data['district']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_district_incorrect()
    {
        $data = $this->getSignUpData();
        $data['district'] = "abc";

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_city_empty()
    {
        $data = $this->getSignUpData();
        unset($data['city']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_city_incorrect()
    {
        $data = $this->getSignUpData();
        $data['city'] = "??";

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_zipcode_empty()
    {
        $data = $this->getSignUpData();
        unset($data['zipcode']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_zipcode_incorrect()
    {
        $data = $this->getSignUpData();
        $data['zipcode'] = "11=111";

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_address_empty()
    {
        $data = $this->getSignUpData();
        unset($data['address']);

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

    /** @test */
    public function form_signup_incorrect_data_address_incorrect()
    {
        $data = $this->getSignUpData();
        $data['address'] = $this->faker->streetName . "?";

        $response = $this->post('/system/signUp', $data)->assertJsonStructure();

        $this->assertCount(0, User::all());
    }

}
