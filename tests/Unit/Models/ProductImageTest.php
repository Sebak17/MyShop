<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      product

    /** @test */
    public function product()
    {
        $category = factory(Category::class)->create();
        $product  = factory(Product::class)->create(['category_id' => $category->id]);

        $image = factory(ProductImage::class)->create(['product_id' => $product->id]);

        $this->assertEquals($image->product->id, $product->id);
    }
}
