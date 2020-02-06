<?php

namespace Tests\Feature\AdminSystem;

use App\Category;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\Helpers as Helper;
use Tests\TestCase;

class ProductAddTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      PRODUCT CREATE
    //

    /** @test */
    public function form_product_create_correct()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productCreate', [
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(1, Product::all());
    }

    /** @test */
    public function form_product_create_incorrect_name_empty()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productCreate', [
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function form_product_create_incorrect_name_wrong()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productCreate', [
            'name'        => $this->faker->sentence(5) . "?",
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function form_product_create_incorrect_price_empty()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productCreate', [
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'description' => $this->faker->text(200),
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function form_product_create_incorrect_price_wrong()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productCreate', [
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'price'       => 'abc',
            'description' => $this->faker->text(200),
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function form_product_create_incorrect_description_empty()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productCreate', [
            'name'     => str_replace(".", "", $this->faker->sentence(5)),
            'price'    => $this->faker->randomFloat(2, 1, 10000),
            'category' => $category->id,
            'params'   => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function form_product_create_incorrect_category_empty()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/productCreate', [
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function form_product_create_incorrect_category_wrong()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/productCreate', [
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'category'    => 'abc',
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, Product::all());
    }

    /** @test */
    public function form_product_create_incorrect_category_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/productCreate', [
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'category'    => 5,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->assertCount(0, Product::all());
    }

    //
    //      PRODUCT ADD IMAGES
    //

    /** @test */
    public function form_product_add_images_1_correct()
    {
        $this->actingAsAdmin();

        $images = array();

        $file1 = __DIR__ . "/../../TestData/photo_1.jpg";
        array_push($images, new UploadedFile($file1, 'photo_1.jpg', filesize($file1), null, null, true));

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg'] ?? "Failed to upload image to product!");
        }

    }

    /** @test */
    public function form_product_add_images_2_correct()
    {
        $this->actingAsAdmin();

        $images = array();

        $file1 = __DIR__ . "/../../TestData/photo_1.jpg";
        array_push($images, new UploadedFile($file1, 'photo_1.jpg', filesize($file1), null, null, true));

        $file2 = __DIR__ . "/../../TestData/photo_2.png";
        array_push($images, new UploadedFile($file2, 'photo_2.png', filesize($file2), null, null, true));

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg'] ?? "Failed to upload images to product!");
        }

    }

    /** @test */
    public function form_product_add_images_incorrect_txtfile()
    {
        $this->actingAsAdmin();

        $images = array();

        $file1 = __DIR__ . "/../../TestData/file_1.txt";
        array_push($images, new UploadedFile($file1, 'file_1.txt', filesize($file1), null, null, true));

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Uploaded text file as a photo!");
        }

    }

    /** @test */
    public function form_product_add_images_incorrect_empty()
    {
        $this->actingAsAdmin();

        $images = array();

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Uploaded zero images!");
        }

    }

    /** @test */
    public function form_product_add_images_incorrect_notimage()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => 'abc',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Uploaded string as a image!");
        }

    }

    //
    //      PRODUCT LIST OF IMAGES
    //

    /** @test */
    public function form_product_list_uploaded_images_correct()
    {
        $this->actingAsAdmin();

        $this->productUploadImages();

        $response = $this->post('/systemAdmin/productLoadOldImages', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg'] ?? "Failed to load images!");
        }

        if (count($result['images']) != 2) {
            $this->fail("Loaded " . count($result['images']) . " from 2!");
        }

    }

    //
    //      PRODUCT REMOVE IMAGE
    //

    /** @test */
    public function form_product_remove_image_correct()
    {
        $this->actingAsAdmin();

        $this->productUploadImages();

        $loadedImages = $this->productUploadedImagesList();

        if (count($loadedImages) == 0) {
            $this->fail($result['msg'] ?? "Failed to remove images! Uploaded images: 0");
        }

        $response = $this->post('/systemAdmin/productAddImageRemove', [
            'name' => current($loadedImages),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg'] ?? "Failed to remove image!");
        }

        if ((count($loadedImages) - 1) != count($this->productUploadedImagesList())) {
            $this->fail("System did not delete the image!");
        }

    }

}
