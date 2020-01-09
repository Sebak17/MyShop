<?php

namespace Tests\Feature;

use App\User;
use App\UserPersonal;
use App\UserLocation;
use App\Admin;
use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemAdminTest extends TestCase
{   
	use WithFaker;
	use RefreshDatabase;

	private $currentUser;
	private $currentAdmin;

	private function actingAsUser() {
        $this->currentUser = factory(User::class)->create();
        $user_personal = factory(UserPersonal::class)->create(['user_id' => $this->currentUser->id]);
        $user_location = factory(UserLocation::class)->create(['user_id' => $this->currentUser->id]);

        $this->actingAs($this->currentUser);
    }

    private function actingAsAdmin() {
        $this->currentAdmin = factory(Admin::class)->create();

        $this->actingAs($this->currentAdmin, 'admin');
    }

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
            'name' => str_replace(".", "", $this->faker->sentence(2)),
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
        	'name' => $this->faker->sentence(2) . " ? ",
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
            'name' => str_replace(".", "", $this->faker->sentence(2)),
            'ovcat' => 1,
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_icon_wrong()
    {
    	$this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name' => str_replace(".", "", $this->faker->sentence(2)),
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
            'name' => str_replace(".", "", $this->faker->sentence(2)),
            'icon' => 'fa-question',
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

    /** @test */
    public function form_category_add_incorrect_overcategory_wrong()
    {
    	$this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryAdd', [
            'name' => str_replace(".", "", $this->faker->sentence(2)),
            'icon' => 'fa-question',
            'ovcat' => 'abc',
        ])->assertJsonStructure();

        $this->assertCount(0, Category::all());
    }

}
