<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\WarehouseItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class ProductTest extends TestCase
{
	use WithFaker;
    use RefreshDatabase;
    use Helper;


    /** @test */
    public function get_buyed_amount_success()
    {
    	$product = $this->createProductWithOrder(1, 3);

        $this->assertEquals($product->getBuyedAmount(), 3);

    }

    /** @test */
    public function get_buyed_items_amount_success()
    {
    	$category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['category_id' => $category->id, 'status' => 'ACTIVE']);

        for($i = 0 ; $i < 3 ; $i++) {
            $item = factory(WarehouseItem::class)->create(['product_id' => $product->id, 'status' => 'BOUGHT']);
        }

        $this->assertEquals($product->getBoughtItemsTotal(), 3);

    }

    /** @test */
    public function get_orders_success()
    {
    	$product = $this->createProductWithOrder(2, 2);

        $this->assertEquals($product->getOrders()->count(), 2);

    }







    /** @test */
    public function is_product_available_inactive()
    {
    	$category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['category_id' => $category->id, 'status' => 'INACTIVE']);

        $this->assertEquals($product->isAvailableToBuy(), false);

    }

    /** @test */
    public function is_product_available_success()
    {
    	$category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['category_id' => $category->id ]);

        $item = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $this->assertEquals($product->isAvailableToBuy(), true);

    }

    /** @test */
    public function is_product_available_empty()
    {
    	$category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['category_id' => $category->id ]);

        $this->assertEquals($product->isAvailableToBuy(), false);

    }
}
