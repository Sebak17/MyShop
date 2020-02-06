<?php

namespace Tests\Feature\AdminSystem;

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
    public function data_dashboard_correct()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/dashboardData', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function data_products_list_correct()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/productLoadList', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function data_categories_list_correct()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/categoryList', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

}
