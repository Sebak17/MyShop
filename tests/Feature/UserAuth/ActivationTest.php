<?php

namespace Tests\Feature\UserAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class ActivationTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;
    use Helper;

    /** @test */
    public function page_not_logged_in_can_see()
    {
        $response = $this->get('/aktywuj_konto')->assertOk();
    }

    /** @test */
    public function page_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/aktywuj_konto')->assertRedirect('/');
    }

    /** @test */
    public function page_authenticated_admins_cannot_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/aktywuj_konto')->assertRedirect('/');
    }

    /** @test */
    public function form_activate_correct_data()
    {
        $this->createUser(['active' => 0]);

        $response = $this->post('/system/activateAccountMail', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'email'      => $this->currentUser->email,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if ($this->currentUser->info->activationHash == null) {
            $this->fail('Activation hash is null!');
        }

    }

    /** @test */
    public function form_activate_incorrect_already_active()
    {
        $this->createUser();

        $response = $this->post('/system/activateAccountMail', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'email'      => $this->faker->unique()->safeEmail,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if ($this->currentUser->info->activationHash != null) {
            $this->fail('Activation hash is set, with active account!');
        }

    }

    /** @test */
    public function form_activate_incorrect_email_notexist()
    {
        $this->createUser(['active' => 0]);

        $response = $this->post('/system/activateAccountMail', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'email'      => $this->faker->unique()->safeEmail,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if ($this->currentUser->info->activationHash != null) {
            $this->fail('Activation hash is set, with wrong account email given!');
        }

    }

    /** @test */
    public function form_activate_incorrect_email()
    {
        $this->createUser(['active' => 0]);

        $response = $this->post('/system/activateAccountMail', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'email'      => $this->currentUser->email . "?",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Activation hash is set, with incorrect account email given!');
        }

    }

    /** @test */
    public function form_activate_incorrect_grecaptcha()
    {
        $this->createUser(['active' => 0]);

        $response = $this->post('/system/activateAccountMail', [
            'grecaptcha' => 'sdadasdsada',
            'email'      => $this->currentUser->email,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail('Activation hash is set, with incorrect grecaptcha given!');
        }

    }

    /** @test */
    public function page_activate_correct_hash()
    {
        $this->createUser(
            ['active' => 0],
            ['info' =>
                ['activationHash' => hash("sha256", time() . "?" . microtime(true))],
            ]
        );

        $response = $this->get('/aktywuj_konto/' . $this->currentUser->info->activationHash);

        $this->currentUser->refresh();

        if ($this->currentUser->info->activationHash != '' || $this->currentUser->active == 0) {
            $this->fail('Activation failed at user page from email link!');
        }

        self::assertTrue(true);
    }

    /** @test */
    public function page_activate_incorrect_hash()
    {
        $this->createUser(
            ['active' => 0],
            ['info' =>
                ['activationHash' => hash("sha256", time() . "?" . microtime(true))],
            ]
        );

        $response = $this->get('/aktywuj_konto/' . hash("sha256", time() . "?" . microtime(true)) . "?");

        if ($this->currentUser->info->activationHash == '' && $this->currentUser->active != 0) {
            $this->fail('Activation failed, wrong hash!');
        }

        self::assertTrue(true);

    }

}
