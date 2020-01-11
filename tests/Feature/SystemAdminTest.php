<?php

namespace Tests\Feature;

use App\User;
use App\UserPersonal;
use App\UserLocation;
use App\Admin;
use App\Category;
use Tests\TestCase;
use Tests\Helpers as Helper;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'name' => $this->faker->word . " " . $this->faker->word,
            'icon' => 'fa-question',
            'ovcat' => 1,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if(!$result['success'])
	        $this->fail($result['msg']);

        $this->assertCount(1, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_name_empty()
    {
    	$this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'icon' => 'fa-question',
            'ovcat' => 1,
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_name_wrong()
    {
    	$this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
        	'name' => $this->faker->word . "?" . $this->faker->word,
            'icon' => 'fa-question',
            'ovcat' => 1,
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_icon_empty()
    {
    	$this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name' => $this->faker->word . " " . $this->faker->word,
            'ovcat' => 1,
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_icon_wrong()
    {
    	$this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name' => $this->faker->word . " " . $this->faker->word,
            'icon' => 'fa?question',
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
            'name' => $this->faker->word . " " . $this->faker->word,
            'icon' => 'fa-question',
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
            'id' => $category->id,
            'name' => $this->faker->word . " " . $this->faker->word,
            'icon' => 'fa-question',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if(!$result['success'])
            $this->fail($result['msg']);

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

        if(!$result['success'])
            $this->fail($result['msg']);

        $this->assertCount(0, Category::all());
    }


    // ADD CHECK METHODS
    /** @test */
    public function form_category_change_order_correct()
    {
        $this->actingAsAdmin();

        $keys = array(1, 2, 3);
        shuffle($keys);

        $data = array();
        $data[factory(Category::class)->create()->id] = $keys[0];
        $data[factory(Category::class)->create()->id] = $keys[1];
        $data[factory(Category::class)->create()->id] = $keys[2];

        $response = $this->post('/systemAdmin/categoryChangeOrder', [
            'newids' => $data,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if(!$result['success'])
            $this->fail($result['msg']);
    }

}
