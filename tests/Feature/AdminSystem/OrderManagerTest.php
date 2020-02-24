<?php

namespace Tests\Feature\AdminSystem;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class OrderManagerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      ORDER CHANGE STATUS
    //

    /** @test */
    public function form_order_change_status_correct()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeStatus', [
            'id'     => $order->id,
            'status' => "SENT",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_order_change_status_incorrect_order_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeStatus', [
            'id'     => 0,
            'status' => "SENT",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Order status changed that not exist!");
        }

    }

    /** @test */
    public function form_order_change_status_incorrect_status_wrong()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeStatus', [
            'id'     => 0,
            'status' => "SENTT",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Order status changed with wrong status!");
        }

    }

    /** @test */
    public function form_order_change_status_incorrect_status_empty()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeStatus', [
            'id' => 0,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Order status changed with empty status!");
        }

    }

    //
    //      ORDER CHANGE DELIVER LOCATION
    //

    /** @test */
    public function form_order_change_deliverloc_correct()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $data = [
            'type'     => "COURIER",
            'district' => $this->faker->numberBetween(1, 16),
            'city'     => $this->faker->city,
            'zipcode'  => $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999),
            'address'  => $this->faker->streetName,
        ];

        $response = $this->post('/systemAdmin/orderChangeDeliverLoc', [
            'id'      => $order->id,
            'deliver' => $data,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_order_change_deliverloc_incorrect_order_notexist()
    {
        $this->actingAsAdmin();

        $data = [
            'type'     => "COURIER",
            'district' => $this->faker->numberBetween(1, 16),
            'city'     => $this->faker->city,
            'zipcode'  => $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999),
            'address'  => $this->faker->streetName,
        ];

        $response = $this->post('/systemAdmin/orderChangeDeliverLoc', [
            'id'      => 0,
            'deliver' => $data,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Order deliver location changed that not exist!");
        }

    }

    /** @test */
    public function form_order_change_deliverloc_incorrect_type_wrong()
    {
        $this->actingAsAdmin();

        $data = [
            'type'     => "AABBCC",
            'district' => $this->faker->numberBetween(1, 16),
            'city'     => $this->faker->city,
            'zipcode'  => $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999),
            'address'  => $this->faker->streetName,
        ];

        $response = $this->post('/systemAdmin/orderChangeDeliverLoc', [
            'id'      => 0,
            'deliver' => $data,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with wrong deliver type!");
        }

    }

    /** @test */
    public function form_order_change_deliverloc_incorrect_type_empty()
    {
        $this->actingAsAdmin();

        $data = [
            'district' => $this->faker->numberBetween(1, 16),
            'city'     => $this->faker->city,
            'zipcode'  => $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999),
            'address'  => $this->faker->streetName,
        ];

        $response = $this->post('/systemAdmin/orderChangeDeliverLoc', [
            'id'      => 0,
            'deliver' => $data,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with empty deliver type!");
        }

    }

    //
    //      ORDER CHANGE PAYMENT
    //

    /** @test */
    public function form_order_change_payment_correct()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangePayment', [
            'id'            => $order->id,
            'paymentMethod' => "PAYU",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_order_change_payment_incorrect_order_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangePayment', [
            'id'            => 0,
            'paymentMethod' => "PAYU",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Order payment changed that not exist!");
        }

    }

    /** @test */
    public function form_order_change_payment_incorrect_payment_wrong()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangePayment', [
            'id'            => $order->id,
            'paymentMethod' => "AABBCC",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with wrong payment!");
        }

    }

    /** @test */
    public function form_order_change_payment_incorrect_payment_empty()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangePayment', [
            'id' => $order->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with empty payment!");
        }

    }

    //
    //      ORDER CHANGE COST
    //

    /** @test */
    public function form_order_change_cost_correct()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeCost', [
            'id'   => $order->id,
            'cost' => $this->faker->randomFloat(2, 1, 10000),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_order_change_cost_incorrect_order_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeCost', [
            'id'   => 0,
            'cost' => $this->faker->randomFloat(2, 1, 10000),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Order payment changed that not exist!");
        }

    }

    /** @test */
    public function form_order_change_cost_incorrect_cost_wrong()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeCost', [
            'id'   => $order->id,
            'cost' => "abbbccc",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with wrong cost!");
        }

    }

    /** @test */
    public function form_order_change_cost_incorrect_cost_empty()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeCost', [
            'id' => $order->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with empty cost!");
        }

    }

    //
    //      ORDER CHANGE PARCEL ID
    //

    /** @test */
    public function form_order_change_parcelid_correct()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeParcelID', [
            'id'       => $order->id,
            'parcelID' => hash("sha256", "qwe"),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_order_change_parcelid_incorrect_order_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeParcelID', [
            'id'       => 0,
            'parcelID' => hash("sha256", "qwe"),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Order payment changed that not exist!");
        }

    }

    /** @test */
    public function form_order_change_parcelid_incorrect_parcelid_wrong()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeParcelID', [
            'id'       => $order->id,
            'parcelID' => $this->faker->text(300),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with wrong parcel ID!");
        }

    }

    /** @test */
    public function form_order_change_parcelid_incorrect_parcelid_empty()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeParcelID', [
            'id' => $order->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with empty parcel ID!");
        }

    }

}
