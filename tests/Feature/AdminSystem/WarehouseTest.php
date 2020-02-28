<?php

namespace Tests\Feature\AdminSystem;

use App\Models\Product;
use App\Models\WarehouseItem;
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
    //      WAREHOUSE LIST
    //

    /** @test */
    public function form_warehouse_list_correct()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $response = $this->post('/systemAdmin/warehouseList', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    //
    //      WAREHOUSE ITEMS LIST
    //

    /** @test */
    public function form_warehouse_itemslist_correct()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $response = $this->post('/systemAdmin/warehouseItemsList', [
            'id' => $product->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_warehouse_itemslist_incorrect_product_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/warehouseItemsList', [
            'id' => 1,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success list of product that not exist!");
        }

    }

     /** @test */
    public function form_warehouse_itemslist_incorrect_product_empty()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/warehouseItemsList', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success list of product with no product id!");
        }

    }

    //
    //      WAREHOUSE ADD ITEM
    //

    /** @test */
    public function form_warehouse_item_add_correct()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemAdmin/warehouseAddItem', [
            'id'     => $product->id,
            'code'   => $this->faker->ean13,
            'status' => 'AVAILABLE',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(1, WarehouseItem::all());
    }

    /** @test */
    public function form_warehouse_item_add_incorrect_product_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/warehouseAddItem', [
            'id'     => 1,
            'code'   => $this->faker->ean13,
            'status' => 'AVAILABLE',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, WarehouseItem::all());
    }

    /** @test */
    public function form_warehouse_item_add_incorrect_product_empty()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemAdmin/warehouseAddItem', [
            'code'   => $this->faker->ean13,
            'status' => 'AVAILABLE',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, WarehouseItem::all());
    }

    /** @test */
    public function form_warehouse_item_add_incorrect_status_error()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemAdmin/warehouseAddItem', [
            'id'     => $product->id,
            'code'   => $this->faker->ean13,
            'status' => 'AAA',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, WarehouseItem::all());
    }

    /** @test */
    public function form_warehouse_item_add_incorrect_status_empty()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemAdmin/warehouseAddItem', [
            'id'     => $product->id,
            'code'   => $this->faker->ean13,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, WarehouseItem::all());
    }

    /** @test */
    public function form_warehouse_item_add_incorrect_code_empty()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemAdmin/warehouseAddItem', [
            'id'     => $product->id,
            'status' => 'AVAILABLE',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, WarehouseItem::all());
    }


    //
    //      WAREHOUSE UPDATE ITEM
    //

    /** @test */
    public function form_warehouse_item_update_correct()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $new_status = "INVISIBLE";

        $response = $this->post('/systemAdmin/warehouseUpdateItem', [
            'product_id'     => $product->id,
            'item_id'   => $item->id,
            'status' => $new_status,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $item->refresh();

        if($item->status != $new_status) {
            $this->fail("Warehouse item status has not been updated!");
        }
    }

    /** @test */
    public function form_warehouse_item_update_incorrect_product_notexist()
    {
        $this->actingAsAdmin();

        $new_status = "INVISIBLE";

        $response = $this->post('/systemAdmin/warehouseUpdateItem', [
            'product_id'     => 2,
            'item_id'   => 2,
            'status' => $new_status,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Updated item without existing product!");
        }
    }

    /** @test */
    public function form_warehouse_item_update_incorrect_product_empty()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $new_status = "INVISIBLE";

        $response = $this->post('/systemAdmin/warehouseUpdateItem', [
            'item_id'   => $item->id,
            'status' => $new_status,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Updated item without product id!");
        }
    }

    /** @test */
    public function form_warehouse_item_update_incorrect_item_notexist()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();

        $new_status = "INVISIBLE";

        $response = $this->post('/systemAdmin/warehouseUpdateItem', [
            'product_id'     => $product->id,
            'item_id'   => 1,
            'status' => $new_status,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Updated item without existing item!");
        }
    }

    /** @test */
    public function form_warehouse_item_update_incorrect_item_empty()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $new_status = "INVISIBLE";

        $response = $this->post('/systemAdmin/warehouseUpdateItem', [
            'product_id'     => $product->id,
            'status' => $new_status,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Updated item without item id!");
        }
    }

    /** @test */
    public function form_warehouse_item_update_incorrect_status_error()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $response = $this->post('/systemAdmin/warehouseUpdateItem', [
            'product_id'     => $product->id,
            'item_id'   => $item->id,
            'status' => "ABC",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
           $this->fail("Updated item with wrong status!");
        }
    }

    /** @test */
    public function form_warehouse_item_update_incorrect_status_empty()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $new_status = "INVISIBLE";

        $response = $this->post('/systemAdmin/warehouseUpdateItem', [
            'product_id'     => $product->id,
            'item_id'   => $item->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Updated item without new status!");
        }
    }

    //
    //      WAREHOUSE HISTORY ITEM
    //

    /** @test */
    public function form_warehouse_item_history_correct()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $response = $this->post('/systemAdmin/warehouseHistoryItem', [
            'product_id'     => $product->id,
            'item_id'   => $item->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }
    }

    /** @test */
    public function form_warehouse_item_history_incorrect_product_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/warehouseHistoryItem', [
            'product_id'     => 1,
            'item_id'   => 1,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Item history without existing product!");
        }
    }

    /** @test */
    public function form_warehouse_item_history_incorrect_product_empty()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $response = $this->post('/systemAdmin/warehouseHistoryItem', [
            'item_id'   => $item->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Item history without product id!");
        }
    }

    /** @test */
    public function form_warehouse_item_history_incorrect_item_empty()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();
        $item    = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

        $response = $this->post('/systemAdmin/warehouseHistoryItem', [
            'product_id'   => $product->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Item history without item id!");
        }
    }


}
