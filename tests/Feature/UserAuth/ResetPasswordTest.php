<?php

namespace Tests\Feature\UserAuth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      PAGES
    //

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

    //
    //      FORM SEND REQUEST
    //

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

    /** @test */
    public function form_resetpassword_email_incorrect_email_empty()
    {
        $this->createUser();

        $response = $this->post('/system/resetPasswordMail', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'phone'      => $this->currentUser->personal->phoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success request for password reset hash with empty email!");
        }

    }

    /** @test */
    public function form_resetpassword_email_incorrect_email_wrong()
    {
        $this->createUser();

        $response = $this->post('/system/resetPasswordMail', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'email'      => $this->currentUser->email . "?",
            'phone'      => $this->currentUser->personal->phoneNumber,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success request for password reset hash with wrong email!");
        }

    }

    /** @test */
    public function form_resetpassword_email_incorrect_phone_empty()
    {
        $this->createUser();

        $response = $this->post('/system/resetPasswordMail', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'email'      => $this->currentUser->email,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success request for password reset hash with phone email!");
        }

    }

    /** @test */
    public function form_resetpassword_email_incorrect_phone_wrong()
    {
        $this->createUser();

        $response = $this->post('/system/resetPasswordMail', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'email'      => $this->currentUser->email,
            'phone'      => $this->currentUser->personal->phoneNumber . "?",
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if ($result['success']) {
            $this->fail("Success request for password reset hash with wrong phone!");
        }

    }

    //
    //      HASH CHECK
    //

    /** @test */
    public function page_resetpassword_correct_hash()
    {
        $this->createUser([], ['info' =>
            ['passwordResetHash' => hash("sha256", time() . "?" . microtime(true))],
        ]
        );

        $response = $this->get('/resetuj_haslo/' . $this->currentUser->info->passwordResetHash)->assertOk();
    }

    /** @test */
    public function page_resetpassword_incorrect_hash()
    {
        $response = $this->get('/resetuj_haslo/aaa')->assertRedirect();
    }

    //
    //      PASSWORD CHANGE
    //

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
            'password'   => $newPassword,
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

    /** @test */
    public function form_resetpassword_change_incorrect_empty_password()
    {
        $this->createUser([], ['info' =>
            ['passwordResetHash' => hash("sha256", time() . "?" . microtime(true))],
        ]
        );

        $this->get('/resetuj_haslo/' . $this->currentUser->info->passwordResetHash)->assertOk();

        $newPassword = 'passwd1';

        $response = $this->post('/system/resetPasswordChange', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->currentUser->refresh();

        if ($result['success']) {
            $this->fail("User password has been changed without password given!");
        }

    }

    /** @test */
    public function form_resetpassword_change_incorrect_old_password()
    {
        $password = 'passwd1';

        $this->createUser([
            'password' => bcrypt($password),
        ],
            ['info' =>
                ['passwordResetHash' => hash("sha256", time() . "?" . microtime(true))],
            ]
        );

        $this->get('/resetuj_haslo/' . $this->currentUser->info->passwordResetHash)->assertOk();

        $response = $this->post('/system/resetPasswordChange', [
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',
            'password'   => $password,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        $this->currentUser->refresh();

        if (!$result['success']) {
            $this->fail($result['msg']);
        }

        if (!password_verify($password, $this->currentUser->password)) {
            $this->fail('New password is not set!');
        }

    }

}
