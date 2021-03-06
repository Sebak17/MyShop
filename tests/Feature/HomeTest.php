<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
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
    public function page_products()
    {
        $category = factory(Category::class)->create();

        for ($i = 0; $i < 4; $i++) {
            factory(Product::class)->create([
                'category_id' => $category->id,
                'status'      => "ACTIVE",
            ]);
        }

        $response = $this->get(route('productsPage'))->assertOk();

    }

    /** @test */
    public function page_products_sites()
    {
        $category = factory(Category::class)->create();

        for ($i = 0; $i < 4; $i++) {
            factory(Product::class)->create([
                'category_id' => $category->id,
                'status'      => "ACTIVE",
            ]);
        }

        foreach (Product::all() as $product) {
            $response = $this->get(route('productPage') . "?id=" . $product->id)->assertOk();
        }

    }

}
