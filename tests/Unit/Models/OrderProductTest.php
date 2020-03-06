<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\WarehouseItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class OrderProductTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      order

    /** @test */
    public function order()
    {
        $order = $this->createOrder(0, 0);

        $category      = factory(Category::class)->create();
        $product       = factory(Product::class)->create(['category_id' => $category->id, 'status' => 'ACTIVE']);
        $item          = factory(WarehouseItem::class)->create(['product_id' => $product->id]);
        $order_product = factory(OrderProduct::class)->create([
            'order_id'          => $order->id,
            'product_id'        => $product->id,
            'warehouse_item_id' => $item->id,
            'price'             => $product->priceCurrent,
            'name'              => $product->title,
        ]);

        $this->assertEquals($order_product->order->id, $order->id);
    }
}
