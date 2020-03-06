<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      order

    /** @test */
    public function order()
    {
        $order = $this->createOrder(1, 0);
        $payment = factory(Payment::class)->create(['order_id' => $order->id, 'amount' => $order->cost]);

        $this->assertEquals($payment->order->id, $order->id);
    }
}
