<?php

namespace Tests\Feature\AdminSystem;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\Helpers as Helper;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      PROMOTION ADD
    //

    /** @test */
    public function form_promotion_add_correct()
    {
        $this->actingAsAdmin();

		$product = $this->createProduct(false);

		$new_price = $this->faker->randomFloat(2, 1, $product->priceNormal);

        $response = $this->post('/systemAdmin/productPromotionAdd', [
            'id' => $product->id,
            'price' => $new_price,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $product->refresh();

        if($product->priceCurrent != $new_price) {
        	$this->fail("Promotion is not activated!");
        }
    }

    /** @test */
    public function form_promotion_add_incorrect_id_empty()
    {
        $this->actingAsAdmin();

		$product = $this->createProduct(false);

		$new_price = $this->faker->randomFloat(2, 1, $product->priceNormal);

        $response = $this->post('/systemAdmin/productPromotionAdd', [
            'price' => $new_price,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Successful changed promotion without id!");
        }
    }

    /** @test */
    public function form_promotion_add_incorrect_price_ishigher()
    {
        $this->actingAsAdmin();

		$product = $this->createProduct(false);

		$new_price = $product->priceNormal + 100;

        $response = $this->post('/systemAdmin/productPromotionAdd', [
            'id' => $product->id,
            'price' => $new_price,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $product->refresh();

        if($product->priceCurrent == $new_price) {
        	$this->fail("New too hight price is now available!");
        }

        if ($result['success']) {
            $this->fail("Successful changed promotion with too high price!");
        }
    }



    //
    //      PROMOTION REMOVE
    //

    /** @test */
    public function form_promotion_remove_correct()
    {
        $this->actingAsAdmin();

		$product = $this->createProduct(false);

		$product->priceCurrent = $this->faker->randomFloat(2, 1, $product->priceNormal);
		$product->save();

        $response = $this->post('/systemAdmin/productPromotionRemove', [
            'id' => $product->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $product->refresh();

        if($product->priceCurrent != $product->priceNormal) {
        	$this->fail("Promotion has not been deactivated!");
        }
    }

    /** @test */
    public function form_promotion_remove_incorrect_id_empty()
    {
        $this->actingAsAdmin();

		$product = $this->createProduct(false);

		$product->priceCurrent = $this->faker->randomFloat(2, 1, $product->priceNormal);
		$product->save();

        $response = $this->post('/systemAdmin/productPromotionRemove', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Successful changed promotion without id!");
        }
    }

}
