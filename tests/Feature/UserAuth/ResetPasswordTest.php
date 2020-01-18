<?php

namespace Tests\Feature\UserAuth;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers as Helper;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;
    use Helper;

    /** @test */
    public function page_not_logged_in_can_see()
    {
        $response = $this->get('/resetuj_haslo')->assertOk();
    }

    /** @test */
    public function page_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/resetuj_haslo')->assertRedirect('/');
    }

    /** @test */
    public function page_authenticated_admins_cannot_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/resetuj_haslo')->assertRedirect('/');
    }

    // ADD CHECK METHODS
    /** @test */
    public function form_resetpassword_email_correct_data()
    {
        $this->createUser();

        $response = $this->post('/system/resetPasswordMail', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'email'      => $this->currentUser->email,
            'phone'      => $this->currentUser->personal->phoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if ($this->currentUser->info->passwordResetHash == null) {
            $this->fail('Reset password hash is null!');
        }
    }

    // ADD CHECK METHODS
    /** @test */
    public function page_resetpassword_correct_hash()
    {
        $this->createUser([], ['info' =>
                ['passwordResetHash' => hash("sha256", time() . "?" . microtime(true))],
            ]
        );

        $response = $this->get('/resetuj_haslo/' . $this->currentUser->info->passwordResetHash)->assertOk();
    }


    // ADD CHECK METHODS
    /** @test */
    public function form_resetpassword_change_correct_data()
    {
        $this->createUser([], ['info' =>
                ['passwordResetHash' => hash("sha256", time() . "?" . microtime(true))],
            ]
        );

        $this->get('/resetuj_haslo/' . $this->currentUser->info->passwordResetHash)->assertOk();

        $newPassword = 'passwd1';

        $response = $this->post('/system/resetPasswordChange', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'password'      => $newPassword,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->currentUser->refresh();

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if (!password_verify($newPassword, $this->currentUser->password)) {
            $this->fail('New password is not set!');
        }

        if ($this->currentUser->info->passwordResetHash != null) {
            $this->fail('Reset password hash is not null after password change!');
        }
    }

}
