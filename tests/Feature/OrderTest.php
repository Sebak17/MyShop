<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use App\UserLocation;
use App\UserPersonal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
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

        if ($this->currentUser == null) {
            $this->createUser();
        }

        $this->actingAs($this->currentUser);
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

    /** @test */
    public function form_load_shopping_cart_only_not_logged_in_users_can_see()
    {
        $response = $this->post('/systemUser/loadShoppingCartProducts')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function form_load_shopping_cart_only_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/loadShoppingCartProducts')->assertOk();
    }

    /** @test */
    public function form_load_shopping_correct_empty()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/loadShoppingCartProducts', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_load_shopping_correct_testdata()
    {
        $this->actingAsUser();

        $this->addProductToUserFavorites();

        $response = $this->post('/systemUser/loadShoppingCartProducts', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (count($result['products']) != 2) {
            $this->fail("Failed to load 2 products at shopping cart! (Loaded " . count($result['products']) . ")");
        }

    }

    /** @test */
    public function form_add_to_shoppingcart_correct()
    {
        $this->actingAsUser();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemUser/addToShoppingCart', [
        	'id' => $product->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_add_to_shoppingcart_incorrect_id_empty()
    {
        $this->actingAsUser();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemUser/addToShoppingCart', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success'])
            $this->fail("Added product to shopping cart without id!");

    }

    /** @test */
    public function form_add_to_shoppingcart_incorrect_id_wrong()
    {
        $this->actingAsUser();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemUser/addToShoppingCart', [
        	'id' => $product->id . " a ",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success'])
            $this->fail("Added product to shopping cart with bad id!");

    }


    /** @test */
    public function form_confirm_shopping_correct()
    {
        $this->actingAsUser();

        $this->addProductToUserFavorites();

        $response = $this->post('/systemUser/confirmShoppingCart', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);
        if (!$result['success']) {
            $this->fail($result['msg']);
        }
    }

    /** @test */
    public function form_confirm_shopping_incorrect_empty()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/confirmShoppingCart', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Confirmed shopping cart without any products!");
        }
    }

}