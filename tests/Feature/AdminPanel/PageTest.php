<?php

namespace Tests\Feature\AdminPanel;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Helpers as Helper;
use Tests\TestCase;

class PageTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase;
    use Helper;

    //
    //      SIGN IN PAGE
    //

    /** @test */
    public function page_signin_not_logged_in_can_see()
    {
        $response = $this->get('/admin/login')->assertOk();
    }

    /** @test */
    public function page_signin_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/login')->assertRedirect('/');
    }

    /** @test */
    public function page_signin_authenticated_admins_cannot_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/login')->assertRedirect('/');
    }

    //
    //      DASHBOARD PAGE
    //

    /** @test */
    public function page_dashboard_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/panel')->assertOk();
    }

    /** @test */
    public function page_dashboard_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/panel')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_dashboard_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/panel')->assertRedirect('/admin/not_authorizated');
    }

    //
    //      CATEGORIES PAGE
    //

    /** @test */
    public function page_categories_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/kategorie')->assertOk();
    }

    /** @test */
    public function page_categories_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/kategorie')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_categories_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/kategorie')->assertRedirect('/admin/not_authorizated');
    }

    //
    //      PRODUCTS LIST PAGE
    //

    /** @test */
    public function page_productslist_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/produkty/lista')->assertOk();
    }

    /** @test */
    public function page_productslist_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/produkty/lista')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_productslist_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/produkty/lista')->assertRedirect('/admin/not_authorizated');
    }

    //
    //      PRODUCT ADD PAGE
    //

    /** @test */
    public function page_productadd_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/produkty/dodaj')->assertOk();
    }

    /** @test */
    public function page_productadd_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/produkty/dodaj')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_productadd_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/produkty/dodaj')->assertRedirect('/admin/not_authorizated');
    }

    //
    //      PRODUCT EDIT PAGE
    //

    /** @test */
    public function page_productedit_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/produkty/edytuj/1')->assertRedirect('/admin/panel');
    }

    /** @test */
    public function page_productedit_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/produkty/edytuj/1')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_productedit_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/produkty/edytuj/1')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_productedit_authenticated_admins_can_see_exist()
    {
        $this->actingAsAdmin();

        $product = factory(Product::class)->create();

        $response = $this->get('/admin/produkty/edytuj/' . $product->id)->assertOk();
    }

    //
    //      ORDERS LIST PAGE
    //

    /** @test */
    public function page_orderslist_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/zamowienia')->assertOk();
    }

    /** @test */
    public function page_orderslist_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/zamowienia')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_orderslist_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/zamowienia')->assertRedirect('/admin/not_authorizated');
    }

    //
    //      ORDERS LIST REALIZING PAGE
    //

    /** @test */
    public function page_orderslistrealising_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/zamowienia/realizacja')->assertOk();
    }

    /** @test */
    public function page_orderslistrealising_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/zamowienia/realizacja')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_orderslistrealising_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/zamowienia/realizacja')->assertRedirect('/admin/not_authorizated');
    }

    //
    //      ORDER INFO PAGE
    //

    /** @test */
    public function page_orderinfo_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/zamowienia/2')->assertOk();
    }

    /** @test */
    public function page_orderinfo_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/zamowienia/2')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_orderinfo_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/zamowienia/2')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_orderinfo_authenticated_admins_can_see_exist()
    {
        $order = $this->createOrder();

        $this->actingAsAdmin();
        $response = $this->get('/admin/zamowienia/' . $order->id)->assertOk();
    }

    //
    //      USERS PAGE
    //

    /** @test */
    public function page_users_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/uzytkownicy')->assertOk();
    }

    /** @test */
    public function page_users_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/uzytkownicy')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_users_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/uzytkownicy')->assertRedirect('/admin/not_authorizated');
    }

    //
    //      USERS PAGE
    //

    /** @test */
    public function page_userinfo_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/uzytkownik')->assertOk();
    }

    /** @test */
    public function page_userinfo_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/uzytkownik')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_userinfo_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/uzytkownik')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_userinfo_authenticated_admins_can_see_exist_id()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $response = $this->get('/admin/uzytkownik?id=' . $this->currentUser->id)->assertOk();
    }

    /** @test */
    public function page_userinfo_authenticated_admins_can_see_exist_email()
    {
        $this->createUser();
        $this->actingAsAdmin();

        $response = $this->get('/admin/uzytkownik?email=' . $this->currentUser->email)->assertOk();
    }

    //
    //      SETTINGS PAGE
    //

    /** @test */
    public function page_settings_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/ustawienia')->assertOk();
    }

    /** @test */
    public function page_settings_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/ustawienia')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_settings_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/ustawienia')->assertRedirect('/admin/not_authorizated');
    }



    /** @test */
    public function page_settings_banners_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/ustawienia/banery')->assertOk();
    }

    /** @test */
    public function page_settings_banners_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/ustawienia/banery')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_settings_banners_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/ustawienia/banery')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_settings_maintenance_authenticated_admins_can_see()
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/ustawienia/przerwa_techniczna')->assertOk();
    }

    /** @test */
    public function page_settings_maintenance_not_logged_in_cannot_see()
    {
        $response = $this->get('/admin/ustawienia/przerwa_techniczna')->assertRedirect('/admin/not_authorizated');
    }

    /** @test */
    public function page_settings_maintenance_authenticated_users_cannot_see()
    {
        $this->actingAsUser();

        $response = $this->get('/admin/ustawienia/przerwa_techniczna')->assertRedirect('/admin/not_authorizated');
    }

}
