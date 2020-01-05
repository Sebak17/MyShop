<?php

namespace Tests\Feature;

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
}
