<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\WarehouseItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class OrderTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      SHOPPING CART CREATING PAGE
    //

    /** @test */
    public function page_shoppingcartcreating_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $response = $this->get('/zamowienie/tworzenie')->assertRedirect('/');
    }

    /** @test */
    public function page_shoppingcartcreating_not_logged_in_cannot_see()
    {
        $response = $this->get('/zamowienie/tworzenie')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function page_shoppingcartcreating_authenticated_admins_cannot_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/zamowienie/tworzenie')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function page_shoppingcartcreating_authenticated_users_can_see_with_products()
    {
        $this->actingAsUser();

        $this->addProductToUserShoppingCart();

        $response = $this->post('/systemUser/confirmShoppingCart', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $response = $this->get('/zamowienie/tworzenie')->assertOk();
    }

    //
    //      ORDER INFO PAGE
    //

    /** @test */
    public function page_orderinfo_authenticated_users_can_see()
    {
        $this->actingAsUser();

        $order = $this->createOrder();

        $response = $this->get('/zamowienie/' . $order->id)->assertOk();
    }

    /** @test */
    public function page_orderinfo_not_logged_in_cannot_see()
    {
        $response = $this->get('/zamowienie/2')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function page_orderinfo_authenticated_admins_cannot_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/zamowienie/2')->assertRedirect('/not_authorizated');
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

        $this->addProductToUserShoppingCart();

        $response = $this->post('/systemUser/loadShoppingCartProducts', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (count($result['products']) != 2) {
            $this->fail("Failed to load 2 products at shopping cart! (Loaded " . count($result['products']) . ")");
        }

    }

    //
    //         PRODUCT ADD TO SHOPPING CART
    //

    /** @test */
    public function form_add_to_shoppingcart_correct()
    {
        $this->actingAsUser();

        $product = factory(Product::class)->create();
        $item = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

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
        $item = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $response = $this->post('/systemUser/addToShoppingCart', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Added product to shopping cart without id!");
        }

    }

    /** @test */
    public function form_add_to_shoppingcart_incorrect_id_wrong()
    {
        $this->actingAsUser();

        $product = factory(Product::class)->create();
        $item = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $response = $this->post('/systemUser/addToShoppingCart', [
            'id' => $product->id . " a ",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Added product to shopping cart with bad id!");
        }

    }

    /** @test */
    public function form_add_to_shoppingcart_incorrect_noitem()
    {
        $this->actingAsUser();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemUser/addToShoppingCart', [
            'id' => $product->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Added product to shopping cart without item in warehouse!");
        }

    }

    //
    //        SHOPPING CART UPDATE
    //

    /** @test */
    public function form_update_shoppingcart_correct()
    {
        $this->actingAsUser();

        $data = array();

        $data['products'] = array();

        for ($i = 0; $i < 3; $i++) {
            $product = factory(Product::class)->create();

            array_push($data['products'], array('id' => $product->id, 'amount' => rand(1, 3)));
        }

        $response = $this->post('/systemUser/updateShoppingCart', $data)->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_update_shoppingcart_incorrect_id_wrong()
    {
        $this->actingAsUser();

        $data = array();

        $data['products'] = array();

        for ($i = 0; $i < 3; $i++) {
            $product = factory(Product::class)->create();

            array_push($data['products'], array('id' => $product->id . "?", 'amount' => rand(1, 3)));
        }

        $response = $this->post('/systemUser/updateShoppingCart', $data)->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Success with wrong id!');
        }

    }

    /** @test */
    public function form_update_shoppingcart_incorrect_amount_wrong()
    {
        $this->actingAsUser();

        $data = array();

        $data['products'] = array();

        for ($i = 0; $i < 3; $i++) {
            $product = factory(Product::class)->create();

            array_push($data['products'], array('id' => $product->id, 'amount' => rand(1, 3) . "?"));
        }

        $response = $this->post('/systemUser/updateShoppingCart', $data)->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Success with wrong amount!');
        }

    }

    //
    //         ORDER CONFIRM
    //

    /** @test */
    public function form_confirm_shopping_correct()
    {
        $this->actingAsUser();

        $this->addProductToUserShoppingCart();

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

    //
    //         ORDER CREATE
    //

    /** @test */
    public function form_create_order_correct_payu_locker()
    {
        $this->actingAsUser();

        $this->addProductToUserShoppingCart();
        $this->confirmShoppingCart();

        $data                = $this->getOrderCreateData(true);
        $data['paymentType'] = "PAYU";

        $response = $this->post('/systemUser/createOrder', $data)->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(1, Order::all());
    }

    /** @test */
    public function form_create_order_correct_paypal_courier()
    {
        $this->actingAsUser();

        $this->addProductToUserShoppingCart();
        $this->confirmShoppingCart();

        $data                = $this->getOrderCreateData();
        $data['paymentType'] = "PAYPAL";

        $response = $this->post('/systemUser/createOrder', $data)->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(1, Order::all());
    }

    /** @test */
    public function form_create_order_incorrect_empty_deliver()
    {
        $this->actingAsUser();

        $this->addProductToUserShoppingCart();
        $this->confirmShoppingCart();

        $data = $this->getOrderCreateData();
        unset($data['deliver']);

        $response = $this->post('/systemUser/createOrder', $data)->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success without info about deliver!");
        }

        $this->assertCount(0, Order::all());
    }

    //
    //         PAYMENT CANCEL
    //

    /** @test */
    public function form_payment_cancel_correct()
    {
        $this->actingAsUser();

        $order = $this->createOrder();

        $response = $this->post('/systemUser/paymentCancel', [
            'id' => $order->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_payment_cancel_incorrect_order_notexist()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/paymentCancel', [
            'id' => 0,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with order that not exist!");
        }

    }

}
