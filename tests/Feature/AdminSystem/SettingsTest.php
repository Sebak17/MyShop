<?php

namespace Tests\Feature\AdminSystem;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class SettingsTest extends TestCase
{

    use RefreshDatabase;
    use Helper;
    use WithFaker;

    //
    //      MAINTENANCE CHANGE STATUS
    //

    /** @test */
    public function form_maintenance_change_enable_correct()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/settingsMaintenanceChange', [
            'enabled' => true,
            'msg'     => $this->faker->text(50),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_maintenance_change_disable_correct()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/settingsMaintenanceChange', [
            'enabled' => false,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_maintenance_change_incorrect_wrong_enabled()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/settingsMaintenanceChange', [
            'enabled' => "aaa",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Maintenance status changed with wrong enabled!");
        }

    }

    //
    //      MAINTENANCE ADD IP
    //

    /** @test */
    public function form_maintenance_ip_add_correct()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/settingsMaintenanceAddIP', [
            'ip' => $this->faker->ipv4,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_maintenance_ip_add_incorrect_ip()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/settingsMaintenanceAddIP', [
            'ip' => $this->faker->ipv4 . ".000",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Added wrong ip to maintenance list!");
        }

    }

    //
    //      MAINTENANCE REMOVE IP
    //

    /** @test */
    public function form_maintenance_ip_remove_correct()
    {
        $this->actingAsAdmin();

        $ip = $this->faker->ipv4;

        $response = $this->post('/systemAdmin/settingsMaintenanceAddIP', [
            'ip' => $ip,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $response = $this->post('/systemAdmin/settingsMaintenanceDelIP', [
            'ip' => $ip,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

    }

    /** @test */
    public function form_maintenance_ip_remove_incorrect_ip()
    {
        $this->actingAsAdmin();

        $ip = $this->faker->ipv4;

        $response = $this->post('/systemAdmin/settingsMaintenanceAddIP', [
            'ip' => $ip,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        $response = $this->post('/systemAdmin/settingsMaintenanceDelIP', [
            'ip' => $ip . ".000",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Added wrong ip to maintenance list!");
        }

    }

}
