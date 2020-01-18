<?php

namespace Tests;

use App\Admin;
use App\Product;
use App\User;
use App\UserInfo;
use App\UserLocation;
use App\UserPersonal;
use Illuminate\Http\UploadedFile;

trait Helpers
{

    private $currentUser;
    private $currentAdmin;

    public function createUser($userData = [], $data = [])
    {
        $this->currentUser = factory(User::class)->create($userData);

        $userInfoData = array();

        if (isset($data['info'])) {
            $userInfoData = $data['info'];
        }

        $userLocationData = array();

        if (isset($data['location'])) {
            $userLocationData = $data['location'];
        }

        $userPersonalData = array();

        if (isset($data['personal'])) {
            $userPersonalData = $data['personal'];
        }

        $userInfoData['user_id']     = $this->currentUser->id;
        $userLocationData['user_id'] = $this->currentUser->id;
        $userPersonalData['user_id'] = $this->currentUser->id;

        $user_info     = factory(UserInfo::class)->create($userInfoData);
        $user_location = factory(UserLocation::class)->create($userLocationData);
        $user_personal = factory(UserPersonal::class)->create($userPersonalData);
    }

    public function actingAsUser()
    {

        if ($this->currentUser == null) {
            $this->createUser();
        }

        $this->actingAs($this->currentUser);
    }

    public function actingAsAdmin()
    {
        $this->currentAdmin = factory(Admin::class)->create();

        $this->actingAs($this->currentAdmin, 'admin');
    }

    public function addProductToUserFavorites()
    {

        if ($this->currentUser == null) {
            $this->createUser();
        }

        for ($i = 0; $i < 2; $i++) {
            $product = factory(Product::class)->create();
            $this->post('/systemUser/addToShoppingCart', ['id' => $product->id])->assertJsonStructure();
        }

    }

    public function confirmShoppingCart()
    {
        $response = $this->post('/systemUser/confirmShoppingCart', [])->assertJsonStructure();
        $result   = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail("Error while confirming shopping cart!");
        }

    }

    public function productUploadImages()
    {
        $images = array();

        $file1 = __DIR__ . "/TestData/photo_1.jpg";
        array_push($images, new UploadedFile($file1, 'photo_1.jpg', filesize($file1), null, null, true));

        $file2 = __DIR__ . "/TestData/photo_2.png";
        array_push($images, new UploadedFile($file2, 'photo_2.png', filesize($file2), null, null, true));

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg'] ?? "Failed to upload images to product!");
        }

    }

    public function productUploadedImagesList()
    {
        $response = $this->post('/systemAdmin/productLoadOldImages', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg'] ?? "Failed to load images!");
        }

        return $result['images'];
    }

    public function getSignUpData()
    {
        $res = [
            'email'      => $this->faker->unique()->safeEmail,
            'pass'       => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => '11-111',
            'address'    => $this->faker->streetName,
        ];
        return $res;
    }

    public function getOrderCreateData()
    {
        $res = [
            'paymentType' => "PAYPAL",
            'clientFName' => $this->faker->firstName,
            'clientSName' => $this->faker->lastName,
            'clientPhone' => $this->faker->numberBetween(111111111, 999999999),
            'deliver'     => [
                'type'     => "COURIER",
                'district' => $this->faker->numberBetween(1, 16),
                'city'     => $this->faker->city,
                'zipcode'  => '11-111',
                'address'  => $this->faker->streetName,
            ],
            'note'        => $this->faker->text(150),
        ];
        return $res;
    }

}
