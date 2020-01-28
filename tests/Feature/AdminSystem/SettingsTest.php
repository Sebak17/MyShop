<?php

namespace Tests\Feature\AdminSystem;

use App\User;
use App\UserPersonal;
use App\UserLocation;
use Tests\Helpers as Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SettingsTest extends TestCase
{

	use RefreshDatabase;
    use Helper;
    use WithFaker;

    // ADD CHECK METHODS
    /** @test */
    public function form_maintenance_change_enable_correct()
    {
        $this->actingAsAdmin();

        $response = $this->post('/systemAdmin/settingsMaintenanceChange', [
			'enabled' => true,
			'msg' => $this->faker->text(50),
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);


        if (!$result['success']) {
            $this->fail($result['msg']);
        }
    }

    // ADD CHECK METHODS
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

    // ADD CHECK METHODS
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

    // ADD CHECK METHODS
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
}
