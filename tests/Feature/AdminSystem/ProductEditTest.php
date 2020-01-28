<?php

namespace Tests\Feature\AdminSystem;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\Helpers as Helper;
use Tests\TestCase;
use App\Category;

class ProductEditTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    /** @test */
    public function form_product_load_data_correct()
    {
        $this->actingAsAdmin();

        $product = $this->createProduct();

        $response = $this->post('/systemAdmin/productLoadCurrent', [
            'id' => $product->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_product_edit_correct()
    {
        $this->actingAsAdmin();

        $product = $this->createProduct();
		$category = factory(Category::class)->create();
        
        $newTitle       = str_replace(".", "", $this->faker->sentence(5));
        $newPrice       = $this->faker->randomFloat(2, 1, 10000);
        $newDescription = $this->faker->text(200);

        $response = $this->post('/systemAdmin/productEdit', [
            'id'          => $product->id,
            'name'       => $newTitle,
            'price'       => $newPrice,
            'description' => $newDescription,
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
            'status'      => "INVISIBLE",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_product_images_list_correct()
    {
        $this->actingAsAdmin();

        $product = $this->createProduct();

        $response = $this->post('/systemAdmin/productEditImageList', [
            'id' => $product->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if (count($result['images']) != 2) {
            $this->fail('There should be 2 images, not ' . count($result['images']) . '!');
        }

    }

    /** @test */
    public function form_product_image_add_correct()
    {
        $this->actingAsAdmin();

        $product = $this->createProduct(false);

        $images = array();

        $file1 = __DIR__ . "/../../TestData/photo_1.jpg";
        array_push($images, new UploadedFile($file1, 'photo_1.jpg', filesize($file1), null, null, true));

        $file2 = __DIR__ . "/../../TestData/photo_2.png";
        array_push($images, new UploadedFile($file2, 'photo_2.png', filesize($file2), null, null, true));

        $response = $this->post('/systemAdmin/productEditImageAdd', [
            'id'     => $product->id,
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);
        $product->refresh();

        if (!$result['success'] || count($product->images) != 2) {
            $this->fail($result['msg'] ?? 'Error while uploading image to product!');
        }

    }

    /** @test */
    public function form_product_image_remove_correct()
    {
        $this->actingAsAdmin();

        $product = $this->createProduct();

        $response = $this->post('/systemAdmin/productEditImageRemove', [
            'id'   => $product->id,
            'name' => $product->images->first()->name,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);
        $product->refresh();

        if (!$result['success'] || count($product->images) != 1) {
            $this->fail($result['msg'] ?? 'Error while removing image! (' . count($product->images) . ')');
        }

    }

}
