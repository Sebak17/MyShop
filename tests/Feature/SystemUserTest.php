<?php

namespace Tests\Feature;

use App\User;
use App\UserLocation;
use App\UserPersonal;
use Tests\TestCase;
use Tests\Helpers as Helper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemUserTest extends TestCase
{

	use WithFaker;
    use RefreshDatabase;
    use Helper;

    /** @test */
    public function form_change_password_correct()
    {
    	$this->actingAsUser();

        $response = $this->post('/systemUser/changePassword', [
            'password_old' => 'password',
            'password_new' => 'password1',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if(!$result['success'])
            $this->fail($result['msg']);
    }

    /** @test */
    public function form_change_password_incorrect_oldpassword_wrong()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changePassword', [
            'password_old' => 'passwor',
            'password_new' => 'password1',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if($result['success'])
            $this->fail('User password changed with wrong old password!');
    }

    /** @test */
    public function form_change_password_incorrect_oldpassword_empty()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changePassword', [
            'password_new' => 'password1',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if($result['success'])
            $this->fail('User password changed without given old password!');
    }

    /** @test */
    public function form_change_password_incorrect_newpassword_empty()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changePassword', [
            'password_old' => 'password',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if($result['success'])
            $this->fail('User password changed without given new password!');
    }



    /** @test */
    public function form_change_personal_correct()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changeDataPersonal', [
            'fname' => $this->faker->firstName,
            'sname' => $this->faker->lastName,
            'phone' => $this->faker->numberBetween(111111111, 999999999),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if(!$result['success'])
            $this->fail($result['msg']);
    }

    /** @test */
    public function form_change_personal_incorrect_fname_empty()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changeDataPersonal', [
            'sname' => $this->faker->lastName,
            'phone' => $this->faker->numberBetween(111111111, 999999999),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if($result['success'])
            $this->fail('User personal data changed without given first name!');
    }


    /** @test */
    public function form_change_personal_incorrect_sname_empty()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changeDataPersonal', [
            'fname' => $this->faker->firstName,
            'phone' => $this->faker->numberBetween(111111111, 999999999),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if($result['success'])
            $this->fail('User personal data changed without given surname!');
    }

    /** @test */
    public function form_change_personal_incorrect_phone_empty()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changeDataPersonal', [
            'fname' => $this->faker->firstName,
            'sname' => $this->faker->lastName,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if($result['success'])
            $this->fail('User personal data changed without given phone number!');
    }

}
