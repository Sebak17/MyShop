<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\Helpers as Helper;
use Tests\TestCase;

class SystemAdminTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    /** @test */
    public function pages_not_logged_in_admins_cannot_see()
    {
        $response = $this->post('/systemAdmin/categoryAdd')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function pages_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->post('/systemAdmin/categoryAdd')->assertRedirect('/not_authorizated');
    }

    /** @test */
    public function pages_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd')->assertOk();
    }

    /** @test */
    public function form_category_add_correct()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name'  => $this->faker->word . " " . $this->faker->word,
            'icon'  => 'fa-question',
            'ovcat' => 1,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_name_empty()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'icon'  => 'fa-question',
            'ovcat' => 1,
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_name_wrong()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name'  => $this->faker->word . "?" . $this->faker->word,
            'icon'  => 'fa-question',
            'ovcat' => 1,
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_icon_empty()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name'  => $this->faker->word . " " . $this->faker->word,
            'ovcat' => 1,
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_icon_wrong()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name'  => $this->faker->word . " " . $this->faker->word,
            'icon'  => 'fa?question',
            'ovcat' => 1,
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_overcategory_empty()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name' => $this->faker->word . " " . $this->faker->word,
            'icon' => 'fa-question',
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_overcategory_wrong()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name'  => $this->faker->word . " " . $this->faker->word,
            'icon'  => 'fa-question',
            'ovcat' => 'abc',
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    // ADD CHECK METHODS
    /** @test */
    public function form_category_edit_correct()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryEdit', [
            'id'   => $category->id,
            'name' => $this->faker->word . " " . $this->faker->word,
            'icon' => 'fa-question',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(1, Category::all());
    }

    // ADD CHECK METHODS
    /** @test */
    public function form_category_remove_correct()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryRemove', [
            'id' => $category->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $this->assertCount(0, Category::all());
    }

    // ADD CHECK METHODS
    /** @test */
    public function form_category_change_order_correct()
    {
        $this->actingAsAdmin();

        $keys = array(1, 2, 3);
        shuffle($keys);

        $data                                         = array();
        $data[factory(Category::class)->create()->id] = $keys[0];
        $data[factory(Category::class)->create()->id] = $keys[1];
        $data[factory(Category::class)->create()->id] = $keys[2];

        $response = $this->post('/systemAdmin/categoryChangeOrder', [
            'newids' => $data,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    // ADD CHECK METHODS
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

    // ADD CHECK METHODS
    /** @test */
    public function form_product_add_images_1_correct()
    {
        $this->actingAsAdmin();

        $images = array();

        $file1 = __DIR__ . "/../TestData/photo_1.jpg";
        array_push($images, new UploadedFile($file1, 'photo_1.jpg', filesize($file1), null, null, true));

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success'])
            $this->fail( $result['msg'] ?? "Failed to upload images to product!" );
        
    }

    /** @test */
    public function form_product_add_images_2_correct()
    {
        $this->actingAsAdmin();

        $images = array();

        $file1 = __DIR__ . "/../TestData/photo_1.jpg";
        array_push($images, new UploadedFile($file1, 'photo_1.jpg', filesize($file1), null, null, true));

        $file2 = __DIR__ . "/../TestData/photo_2.png";
        array_push($images, new UploadedFile($file2, 'photo_2.png', filesize($file2), null, null, true));

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success'])
            $this->fail( $result['msg'] ?? "Failed to upload images to product!" );
        
    }

    /** @test */
    public function form_product_add_images_incorrect_txtfile()
    {
        $this->actingAsAdmin();

        $images = array();

        $file1 = __DIR__ . "/../TestData/file_1.txt";
        array_push($images, new UploadedFile($file1, 'file_1.txt', filesize($file1), null, null, true));

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success'])
            $this->fail("Uploaded text file as a photo!" );
        
    }

    /** @test */
    public function form_product_list_uploaded_images_correct()
    {
        $this->actingAsAdmin();

        $this->productUploadImages();

        $response = $this->post('/systemAdmin/productLoadOldImages', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success'])
            $this->fail( $result['msg'] ?? "Failed to load images!" );

        if (count($result['images']) != 2)
            $this->fail("Loaded " . count($result['images']) . " from 2!" );
        
    }


    /** @test */
    public function form_product_remove_image_correct()
    {
        $this->actingAsAdmin();

        $this->productUploadImages();

        $loadedImages = $this->productUploadedImagesList();

        if(count($loadedImages) == 0) {
            $this->fail( $result['msg'] ?? "Failed to remove images! Uploaded images: 0" );
        }

        $response = $this->post('/systemAdmin/productAddImageRemove', [
            'name' => current($loadedImages),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success'])
            $this->fail( $result['msg'] ?? "Failed to remove image!" );

        if((count($loadedImages) - 1) != count($this->productUploadedImagesList()))
            $this->fail("System did not delete the image!");
        
    }

}
