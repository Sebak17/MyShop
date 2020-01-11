<?php

namespace Tests;

use App\Admin;
use App\Order;
use App\Product;
use App\User;
use App\UserLocation;
use App\UserPersonal;

trait Helpers
{

    private $currentUser;
    private $currentAdmin;

    public function createUser()
    {
        $this->currentUser = factory(User::class)->create();
        $user_personal     = factory(UserPersonal::class)->create(['user_id' => $this->currentUser->id]);
        $user_location     = factory(UserLocation::class)->create(['user_id' => $this->currentUser->id]);
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

}
