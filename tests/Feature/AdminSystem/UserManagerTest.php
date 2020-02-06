<?php

namespace Tests\Feature\AdminSystem;

use App\Ban;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class UserManagerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      USER BAN
    //

    /** @test */
    public function form_user_ban_correct()
    {
        $this->createUser();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/userBan', [
            'id'     => $this->currentUser->id,
            'reason' => $this->faker->text(150),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(1, Ban::all());
    }

    /** @test */
    public function form_user_ban_incorrect_user_notexist()
    {
        $this->createUser();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/userBan', [
            'id'     => 0,
            'reason' => $this->faker->text(150),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success user ban that not exist!");
        }

        $this->assertCount(0, Ban::all());
    }

    /** @test */
    public function form_user_ban_incorrect_reason_wrong()
    {
        $this->createUser();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/userBan', [
            'id'     => $this->currentUser->id,
            'reason' => $this->faker->text(150) . "?",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success user ban with wrong reason!");
        }

        $this->assertCount(0, Ban::all());
    }

    /** @test */
    public function form_user_ban_incorrect_reason_empty()
    {
        $this->createUser();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/userBan', [
            'id' => $this->currentUser->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success user ban with empty reason!");
        }

        $this->assertCount(0, Ban::all());
    }

    //
    //      USER UNBAN
    //

    /** @test */
    public function form_user_unban_correct()
    {
        $this->createUser();
        $this->banUser();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/userUnban', [
            'id' => $this->currentUser->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(0, Ban::all());
    }

    /** @test */
    public function form_user_unban_incorrect_user_notexist()
    {
        $this->createUser();
        $this->banUser();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/userUnban', [
            'id' => 0,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success user unban that not exist!");
        }

        $this->assertCount(1, Ban::all());
    }

    //
    //      USER CHANGE PERSONAL DATA
    //

    /** @test */
    public function form_user_change_personal_correct()
    {
        $this->createUser();

        $this->actingAsAdmin();

        $newFirstName   = $this->faker->firstName;
        $newSurName     = $this->faker->lastName;
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999);

        $response = $this->post('/systemAdmin/userChangePersonal', [
            'id'    => $this->currentUser->id,
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
    public function form_user_change_personal_incorrect_user_notexist()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newFirstName   = $this->faker->firstName;
        $newSurName     = $this->faker->lastName;
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999);

        $response = $this->post('/systemAdmin/userChangePersonal', [
            'id'    => 0,
            'fname' => $newFirstName,
            'sname' => $newSurName,
            'phone' => $newPhoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed without user!");
        }

    }

    /** @test */
    public function form_user_change_personal_incorrect_firstname_wrong()
    {
        $this->createUser();

        $this->actingAsAdmin();

        $newFirstName   = $this->faker->firstName . "?";
        $newSurName     = $this->faker->lastName;
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999);

        $response = $this->post('/systemAdmin/userChangePersonal', [
            'id'    => $this->currentUser->id,
            'fname' => $newFirstName,
            'sname' => $newSurName,
            'phone' => $newPhoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with wrong firstname!");
        }

    }

    /** @test */
    public function form_user_change_personal_incorrect_firstname_empty()
    {
        $this->createUser();

        $this->actingAsAdmin();

        $newFirstName   = $this->faker->firstName;
        $newSurName     = $this->faker->lastName;
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999);

        $response = $this->post('/systemAdmin/userChangePersonal', [
            'id'    => $this->currentUser->id,
            'sname' => $newSurName,
            'phone' => $newPhoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with empty firstname!");
        }

    }

    /** @test */
    public function form_user_change_personal_incorrect_surname_wrong()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newFirstName   = $this->faker->firstName;
        $newSurName     = $this->faker->lastName . "?";
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999);

        $response = $this->post('/systemAdmin/userChangePersonal', [
            'id'    => $this->currentUser->id,
            'fname' => $newFirstName,
            'sname' => $newSurName,
            'phone' => $newPhoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with wrong surname!");
        }

    }

    /** @test */
    public function form_user_change_personal_incorrect_surname_empty()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newFirstName   = $this->faker->firstName;
        $newSurName     = $this->faker->lastName;
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999);

        $response = $this->post('/systemAdmin/userChangePersonal', [
            'id'    => $this->currentUser->id,
            'fname' => $newFirstName,
            'phone' => $newPhoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with empty surname!");
        }

    }

    /** @test */
    public function form_user_change_personal_incorrect_phone_wrong()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newFirstName   = $this->faker->firstName;
        $newSurName     = $this->faker->lastName;
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999) . "?";

        $response = $this->post('/systemAdmin/userChangePersonal', [
            'id'    => $this->currentUser->id,
            'fname' => $newFirstName,
            'sname' => $newSurName,
            'phone' => $newPhoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with wrong phone!");
        }

    }

    /** @test */
    public function form_user_change_personal_incorrect_phone_empty()
    {
        $this->createUser();

        $this->actingAsAdmin();

        $newFirstName   = $this->faker->firstName;
        $newSurName     = $this->faker->lastName;
        $newPhoneNumber = $this->faker->numberBetween(111111111, 999999999);

        $response = $this->post('/systemAdmin/userChangePersonal', [
            'id'    => $this->currentUser->id,
            'fname' => $newFirstName,
            'sname' => $newSurName,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with empty phone!");
        }

    }

    //
    //      USER CHANGE LOCATION DATA
    //

    /** @test */
    public function form_user_change_location_correct()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'       => $this->currentUser->id,
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
    public function form_user_change_location_incorrect_user_notexist()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'       => 0,
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed without user!");
        }

    }

    /** @test */
    public function form_user_change_location_incorrect_district_wrong()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = 99999;
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'       => $this->currentUser->id,
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with wrong district!");
        }

    }

    /** @test */
    public function form_user_change_location_incorrect_district_empty()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'      => $this->currentUser->id,
            'city'    => $newCity,
            'zipcode' => $newZipCode,
            'address' => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with empty district!");
        }

    }

    /** @test */
    public function form_user_change_location_incorrect_city_wrong()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city . "?";
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'       => $this->currentUser->id,
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with wrong city!");
        }

    }

    /** @test */
    public function form_user_change_location_incorrect_city_empty()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'       => $this->currentUser->id,
            'district' => $newDistrict,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with empty city!");
        }

    }

    /** @test */
    public function form_user_change_location_incorrect_zipcode_wrong()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '+' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'       => $this->currentUser->id,
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with wrong zipcode!");
        }

    }

    /** @test */
    public function form_user_change_location_incorrect_zipcode_empty()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'       => $this->currentUser->id,
            'district' => $newDistrict,
            'city'     => $newCity,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with empty zipcode!");
        }

    }

    /** @test */
    public function form_user_change_location_incorrect_address_wrong()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName . "?";

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'       => $this->currentUser->id,
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
            'address'  => $newAddress,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with wrong address!");
        }

    }

    /** @test */
    public function form_user_change_location_incorrect_address_empty()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $newDistrict = $this->faker->numberBetween(1, 16);
        $newCity     = $this->faker->city;
        $newZipCode  = $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999);
        $newAddress  = $this->faker->streetName;

        $response = $this->post('/systemAdmin/userChangeLocation', [
            'id'       => $this->currentUser->id,
            'district' => $newDistrict,
            'city'     => $newCity,
            'zipcode'  => $newZipCode,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("User data changed with empty address!");
        }

    }

}
