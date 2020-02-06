<?php

namespace Tests\Feature\AdminSystem;

use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\Helpers as Helper;
use Tests\TestCase;

class ProductEditTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      LOAD CURRENT PRODUCT
    //

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
    public function form_product_load_data_incorrect_product_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/productLoadCurrent', [
            'id' => 0,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with product that not exist!");
        }

    }

    //
    //      PRODUCT EDIT
    //

    /** @test */
    public function form_product_edit_correct()
    {
        $this->actingAsAdmin();

        $product  = $this->createProduct();
        $category = factory(Category::class)->create();

        $newTitle       = str_replace(".", "", $this->faker->sentence(5));
        $newPrice       = $this->faker->randomFloat(2, 1, 10000);
        $newDescription = $this->faker->text(200);

        $response = $this->post('/systemAdmin/productEdit', [
            'id'          => $product->id,
            'name'        => $newTitle,
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
    public function form_product_edit_incorrect_name_empty()
    {
        $this->actingAsAdmin();

        $product  = $this->createProduct();
        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productEdit', [
            'id'          => $product->id,
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with empty name!");
        }

    }

    /** @test */
    public function form_product_edit_incorrect_name_wrong()
    {
        $this->actingAsAdmin();

        $product  = $this->createProduct();
        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productEdit', [
            'id'          => $product->id,
            'name'        => $this->faker->sentence(5) . "?",
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with wrong name!");
        }

    }

    /** @test */
    public function form_product_edit_incorrect_price_empty()
    {
        $this->actingAsAdmin();

        $product  = $this->createProduct();
        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productEdit', [
            'id'          => $product->id,
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'description' => $this->faker->text(200),
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with empty price!");
        }

    }

    /** @test */
    public function form_product_edit_incorrect_price_wrong()
    {
        $this->actingAsAdmin();

        $product  = $this->createProduct();
        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productEdit', [
            'id'          => $product->id,
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'price'       => 'abc',
            'description' => $this->faker->text(200),
            'category'    => $category->id,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with wrong price!");
        }

    }

    /** @test */
    public function form_product_edit_incorrect_description_empty()
    {
        $this->actingAsAdmin();

        $product  = $this->createProduct();
        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/productEdit', [
            'id'       => $product->id,
            'name'     => str_replace(".", "", $this->faker->sentence(5)),
            'price'    => $this->faker->randomFloat(2, 1, 10000),
            'category' => $category->id,
            'params'   => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with empty description!");
        }

    }

    /** @test */
    public function form_product_edit_incorrect_category_empty()
    {
        $this->actingAsAdmin();

        $product = $this->createProduct();

        $response = $this->post('/systemAdmin/productEdit', [
            'id'          => $product->id,
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with empty category!");
        }

    }

    /** @test */
    public function form_product_edit_incorrect_category_wrong()
    {
        $this->actingAsAdmin();

        $product = $this->createProduct();

        $response = $this->post('/systemAdmin/productEdit', [
            'id'          => $product->id,
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'category'    => 'abc',
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with wrong category!");
        }

    }

    /** @test */
    public function form_product_edit_incorrect_category_notexist()
    {
        $this->actingAsAdmin();
        $product = $this->createProduct();

        $response = $this->post('/systemAdmin/productEdit', [
            'id'          => $product->id,
            'name'        => str_replace(".", "", $this->faker->sentence(5)),
            'price'       => $this->faker->randomFloat(2, 1, 10000),
            'description' => $this->faker->text(200),
            'category'    => 5,
            'params'      => '[{"name":"Nazwa","value":"Wartość"}]',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with category that not exist!");
        }

    }

    //
    //      PRODUCT IMAGE LIST
    //

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
    public function form_product_images_list_incorrect_product_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/productEditImageList', [
            'id' => 0,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success with product that not exist!");
        }

    }

    //
    //      PRODUCT ADD IMAGE
    //

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
    public function form_product_image_add_incorrect_product_notexist()
    {
        $this->actingAsAdmin();

        $images = array();

        $file1 = __DIR__ . "/../../TestData/photo_1.jpg";
        array_push($images, new UploadedFile($file1, 'photo_1.jpg', filesize($file1), null, null, true));

        $file2 = __DIR__ . "/../../TestData/photo_2.png";
        array_push($images, new UploadedFile($file2, 'photo_2.png', filesize($file2), null, null, true));

        $response = $this->post('/systemAdmin/productEditImageAdd', [
            'id'     => 0,
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Uploaded image to product that not exist!');
        }

    }

    /** @test */
    public function form_product_image_add_incorrect_txtfile()
    {
        $this->actingAsAdmin();

        $product = $this->createProduct(false);

        $images = array();

        $file1 = __DIR__ . "/../../TestData/file_1.txt";
        array_push($images, new UploadedFile($file1, 'file_1.txt', filesize($file1), null, null, true));

        $response = $this->post('/systemAdmin/productEditImageAdd', [
            'id'     => $product->id,
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);
        $product->refresh();

        if ($result['success']) {
            $this->fail('Uploaded text file as a photo!!');
        }

    }

    /** @test */
    public function form_product_image_add_incorrect_empty()
    {
        $this->actingAsAdmin();

        $product = $this->createProduct(false);

        $images = array();

        $response = $this->post('/systemAdmin/productEditImageAdd', [
            'id'     => $product->id,
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);
        $product->refresh();

        if ($result['success']) {
            $this->fail('Uploaded 0 of images!');
        }

    }

    //
    //      PRODUCT REMOVE IMAGE
    //

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

    /** @test */
    public function form_product_image_remove_incorrect_product_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/productEditImageRemove', [
            'id'   => 0,
            'name' => "aaa.jpg",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Removed image from product that not exist!');
        }

    }

}
