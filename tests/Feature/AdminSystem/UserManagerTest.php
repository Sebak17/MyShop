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

    // ADD CHECK METHODS
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

    // ADD CHECK METHODS
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

    }

    // ADD CHECK METHODS
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

    // ADD CHECK METHODS
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

}
