<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function page_home_is_working()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function page_products_sites()
    {
    	foreach (Product::all() as $product) {
    		$response = $this->get(route('productPage') . "?id=" . $product->id)->assertOk();
    	}
    }
}
