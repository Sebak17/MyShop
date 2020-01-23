<?php

namespace Tests\Feature\UserSystem;

use App\User;
use App\UserLocation;
use App\UserPersonal;
use Tests\TestCase;
use Tests\Helpers as Helper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    /** @test */
    public function form_change_password_correct()
    {
    	$this->actingAsUser();

        $newPassword = 'password1';

        $response = $this->post('/systemUser/changePassword', [
            'password_old' => 'password',
            'password_new' => $newPassword,
        ])->assertJsonStructure();

        $this->currentUser->refresh();
        $result = json_decode($response->getContent(), true);

        if(!$result['success'])
            $this->fail($result['msg']);

        if(!password_verify($newPassword, $this->currentUser->password))
            $this->fail('Password has not been changed!');

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

        $newFirstName = $this->faker->firstName;
        $newSurName = $this->faker->lastName;
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999);

        $response = $this->post('/systemUser/changeDataPersonal', [
            'fname' => $newFirstName,
            'sname' => $newSurName,
            'phone' => $newPhoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if(!$result['success'])
            $this->fail($result['msg']);

        if($this->currentUser->personal->firstname != $newFirstName)
            $this->fail('First name have old value!');

        if($this->currentUser->personal->surname != $newSurName)
            $this->fail('Surname have old value!');
        
        if($this->currentUser->personal->phoneNumber != $newPhoneNumber)
            $this->fail('Phone number have old value!');

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



    // TODO - USER LOCATION CHANGE
}
