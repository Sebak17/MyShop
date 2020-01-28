<?php

namespace Tests\Feature\UserSystem;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

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

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if (!password_verify($newPassword, $this->currentUser->password)) {
            $this->fail('Password has not been changed!');
        }

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

        if ($result['success']) {
            $this->fail('User password changed with wrong old password!');
        }

    }

    /** @test */
    public function form_change_password_incorrect_oldpassword_empty()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changePassword', [
            'password_new' => 'password1',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User password changed without given old password!');
        }

    }

    /** @test */
    public function form_change_password_incorrect_newpassword_empty()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changePassword', [
            'password_old' => 'password',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User password changed without given new password!');
        }

    }

    /** @test */
    public function form_change_personal_correct()
    {
        $this->actingAsUser();

        $newFirstName   = $this->faker->firstName;
        $newSurName     = $this->faker->lastName;
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999);

        $response = $this->post('/systemUser/changeDataPersonal', [
            'fname' => $newFirstName,
            'sname' => $newSurName,
            'phone' => $newPhoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if ($this->currentUser->personal->firstname != $newFirstName) {
            $this->fail('First name have old value!');
        }

        if ($this->currentUser->personal->surname != $newSurName) {
            $this->fail('Surname have old value!');
        }

        if ($this->currentUser->personal->phoneNumber != $newPhoneNumber) {
            $this->fail('Phone number have old value!');
        }

    }

    /** @test */
    public function form_change_personal_incorrect_fname_wrong() 
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changeDataPersonal', [
            'fname' => $this->faker->firstName . "-",
            'sname' => $this->faker->lastName,
            'phone' => $this->faker->numberBetween(111111111, 999999999),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User personal data changed with wrong firstname!');
        }
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

        if ($result['success']) {
            $this->fail('User personal data changed without given first name!');
        }

    }

    /** @test */
    public function form_change_personal_incorrect_sname_wrong() 
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changeDataPersonal', [
            'fname' => $this->faker->firstName,
            'sname' => $this->faker->lastName . "1",
            'phone' => $this->faker->numberBetween(111111111, 999999999),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User personal data changed with wrong surname!');
        }
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

        if ($result['success']) {
            $this->fail('User personal data changed without given surname!');
        }

    }

    /** @test */
    public function form_change_personal_incorrect_phone_wrong() 
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changeDataPersonal', [
            'fname' => $this->faker->firstName,
            'sname' => $this->faker->lastName,
            'phone' => $this->faker->numberBetween(111111111, 999999999) . "a",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User personal data changed with wrong phone number!');
        }
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

        if ($result['success']) {
            $this->fail('User personal data changed without given phone number!');
        }

    }

    /** @test */
    public function form_change_location_correct()
    {
        $this->actingAsUser();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemUser/changeDataLocation', [
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if ($this->currentUser->location->district != $newDistrict) {
            $this->fail('District have old value!');
        }

        if ($this->currentUser->location->city != $newCity) {
            $this->fail('City have old value!');
        }

        if ($this->currentUser->location->zipcode != $newZipCode) {
            $this->fail('Zipcode have old value!');
        }

        if ($this->currentUser->location->address != $newAddress) {
            $this->fail('Address have old value!');
        }

    }


    /** @test */
    public function form_change_location_incorrect_district_wrong() 
    {
        $this->actingAsUser();

        $newDistrict = 'abc';
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemUser/changeDataLocation', [
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User location changed with wrong district!');
        }
    }

    /** @test */
    public function form_change_location_incorrect_district_empty() 
    {
        $this->actingAsUser();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemUser/changeDataLocation', [
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User location changed without district given!');
        }
    }

    /** @test */
    public function form_change_location_incorrect_city_wrong() 
    {
        $this->actingAsUser();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city . "?";
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemUser/changeDataLocation', [
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User location changed with wrong city!');
        }
    }

    /** @test */
    public function form_change_location_incorrect_city_empty() 
    {
        $this->actingAsUser();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemUser/changeDataLocation', [
            'district' => $newDistrict,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User location changed without city given!');
        }
    }

    /** @test */
    public function form_change_location_incorrect_zipcode_wrong() 
    {
        $this->actingAsUser();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '?' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemUser/changeDataLocation', [
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User location changed with wrong zipcode!');
        }
    }

    /** @test */
    public function form_change_location_incorrect_zipcode_empty() 
    {
        $this->actingAsUser();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemUser/changeDataLocation', [
            'district' => $newDistrict,
            'city'     => $newCity,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User location changed without zipcode given!');
        }
    }

    /** @test */
    public function form_change_location_incorrect_address_wrong() 
    {
        $this->actingAsUser();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName . "?";

        $response = $this->post('/systemUser/changeDataLocation', [
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User location changed with wrong address!');
        }
    }

    /** @test */
    public function form_change_location_incorrect_address_empty() 
    {
        $this->actingAsUser();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemUser/changeDataLocation', [
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('User location changed without address given!');
        }
    }

}
