<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Payment;
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
    //      user

    /** @test */
    public function user()
    {
        $order = $this->createOrder(0, 0);

        $this->assertEquals($order->user->id, $this->currentUser->id);
    }


    //
    //      products

    /** @test */
    public function products()
    {
        $order = $this->createOrder(0, 0);

        $category      = factory(Category::class)->create();
        $product       = factory(Product::class)->create(['category_id' => $category->id, 'status' => 'ACTIVE']);

        for($i = 0 ; $i < 2 ; $i++) {
        	$item          = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

	        $order_product = factory(OrderProduct::class)->create([
	            'order_id'          => $order->id,
	            'product_id'        => $product->id,
	            'warehouse_item_id' => $item->id,
	            'price'             => $product->priceCurrent,
	            'name'              => $product->title,
	        ]);
        }

        $this->assertEquals($order->products->count(), 2);
    }


    //
    //      payments

    /** @test */
    public function payments()
    {
        $order = $this->createOrder(1, 2);

        $payment = factory(Payment::class)->create(['order_id' => $order->id, 'amount' => $order->cost]);

        $this->assertEquals($order->payments->count(), 1);
    }

    //
    //      getCurrentPayment

    /** @test */
    public function get_current_payment_success()
    {
        $order = $this->createOrder(1, 2);

        $payment = factory(Payment::class)->create(['order_id' => $order->id, 'amount' => $order->cost]);

        $this->assertEquals($order->getCurrentPayment()->id, $payment->id);
    }

    /** @test */
    public function get_current_payment_null()
    {
        $order = $this->createOrder(1, 2);

        $this->assertEquals($order->getCurrentPayment(), null);
    }
}
