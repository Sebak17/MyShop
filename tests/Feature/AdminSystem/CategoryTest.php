<?php

namespace Tests\Feature\AdminSystem;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      CATEGORY ADD
    //

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

    //
    //      CATEGORY EDIT
    //

    /** @test */
    public function form_category_edit_correct()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryEdit', [
            'id'   => $category->id,
            'name' => str_replace(".", "", $this->faker->text(18)),
            'icon' => 'fa-question',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_category_edit_incorrect_id_notexist()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryEdit', [
            'id'   => 2,
            'name' => str_replace(".", "", $this->faker->text(18)),
            'icon' => 'fa-question',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Category edit success with id not exist!');
        }

    }

    /** @test */
    public function form_category_edit_incorrect_id_empty()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryEdit', [
            'name' => $this->faker->word . " " . $this->faker->word,
            'icon' => 'fa-question',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Category edit success with not id given!');
        }

    }

    /** @test */
    public function form_category_edit_incorrect_name_empty()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryEdit', [
            'id'   => $category->id,
            'icon' => 'fa-question',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Category edit success with empty name!');
        }

    }

    /** @test */
    public function form_category_edit_incorrect_name_wrong()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryEdit', [
            'id'   => $category->id,
            'name' => $this->faker->word . "?" . $this->faker->word,
            'icon' => 'fa-question',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Category edit success with wrong name!');
        }

    }

    /** @test */
    public function form_category_edit_incorrect_icon_empty()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryEdit', [
            'id'   => $category->id,
            'name' => $this->faker->word . "?" . $this->faker->word,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Category edit success with empty icon!');
        }

    }

    /** @test */
    public function form_category_edit_incorrect_icon_wrong()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryEdit', [
            'id'   => $category->id,
            'name' => $this->faker->word . "?" . $this->faker->word,
            'icon' => 'fa?question',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Category edit success with wrong icon!');
        }

    }

    //
    //      CATEGORY REMOVE
    //

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

    /** @test */
    public function form_category_remove_incorrect_id_empty()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryRemove', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Category removed with no id given!');
        }

        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function form_category_remove_incorrect_id_wrong()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryRemove', [
            'id' => 'abc',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Category removed with wrong id given!');
        }

        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function form_category_remove_incorrect_id_notexist()
    {
        $this->actingAsAdmin();

        $category = factory(Category::class)->create();

        $response = $this->post('/systemAdmin/categoryRemove', [
            'id' => 'abc',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Category removed with wrong id that not exist!');
        }

        $this->assertCount(1, Category::all());
    }

    //
    //      CATEGORY CHANGE ORDER
    //

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

    /** @test */
    public function form_category_change_order_incorrect_empty()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryChangeOrder', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Success categories change order with empty array!');
        }

    }

    /** @test */
    public function form_category_change_order_correct_category_notexist()
    {
        $this->actingAsAdmin();

        $keys = array(1, 2, 3);
        shuffle($keys);

        $data                                         = array();
        $data[factory(Category::class)->create()->id] = $keys[0];
        $data[factory(Category::class)->create()->id] = $keys[1];
        $data[5]                                      = $keys[2];

        $response = $this->post('/systemAdmin/categoryChangeOrder', [
            'newids' => $data,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

}
