<?php

namespace Tests\Feature\UserSystem;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class GeneralTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use Helper;

    /** @test */
    public function form_change_favorite_correct()
    {
        $this->actingAsUser();

        $product = factory(Product::class)->create();

        $response = $this->post('/systemUser/changeFavoriteStatus', [
            'id' => $product->id,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_change_favorite_incorrect_not_exist()
    {
        $this->actingAsUser();

        $response = $this->post('/systemUser/changeFavoriteStatus', [
            'id' => 5,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Change favorite status with no product!');
        }

    }

}
