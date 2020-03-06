<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\WarehouseItem;
use App\Models\WarehouseItemHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class WarehouseTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      product

    /** @test */
    public function product()
    {
        $category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['category_id' => $category->id, 'status' => 'ACTIVE']);

        $item = factory(WarehouseItem::class)->create(['product_id' => $product->id, 'status' => 'BOUGHT']);

        $this->assertEquals($item->product->id, $product->id);
    }


    //
    //      history

    /** @test */
    public function history()
    {
        $category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['category_id' => $category->id, 'status' => 'ACTIVE']);
        $item = factory(WarehouseItem::class)->create(['product_id' => $product->id, 'status' => 'BOUGHT']);

        for($i = 0 ; $i < 3 ; $i++) {
        	$history = factory(WarehouseItemHistory::class)->create(['item_id' => $item->id]);
        }

        $this->assertEquals($item->history->count(), 3);
    }
}
