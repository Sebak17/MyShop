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

    // ADD CHECK METHODS
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

    // ADD CHECK METHODS
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

    // ADD CHECK METHODS
    /** @test */
    public function form_order_change_payment_correct()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangePayment', [
            'id' => $order->id,
            'paymentMethod' => "PAYU",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }
    }

    // ADD CHECK METHODS
    /** @test */
    public function form_order_change_cost_correct()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeCost', [
            'id' => $order->id,
            'cost' => $this->faker->randomFloat(2, 1, 10000),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }
    }

    // ADD CHECK METHODS
    /** @test */
    public function form_order_change_parcelid_correct()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/orderChangeParcelID', [
            'id' => $order->id,
            'parcelID' => hash("sha256", "qwe"),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }
    }

}
