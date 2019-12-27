<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::fallback(function () {
    return view('errors.404');
});

Route::get('/', 'HomeController@index')->name('home');

Route::any('/produkty', 'HomeController@productsPage')->name('productsPage');
Route::get('/produkt', 'HomeController@productPage')->name('productPage');

//
//      AUTH SITES
//

Route::group([], function () {

    Route::get('/not_authorizated', function () {
        return view('auth.not_authorizated');
    })->name('not_authorizated');

    Route::get('/logowanie', 'AuthorizationController@loginPage')->name('loginPage');
    Route::get('/rejestracja', 'AuthorizationController@registerPage')->name('registerPage');

    Route::get('/aktywuj_konto', 'AuthorizationController@activeAccountPage')->name('activeAccountPage');
    Route::get('/aktywuj_konto/{hash}', 'AuthorizationController@activeAccountCheckPage')->name('activeAccountCheckPage');

    Route::get('/resetuj_haslo', 'AuthorizationController@resetPasswordPage')->name('resetPasswordPage');
    Route::get('/resetuj_haslo/{hash}', 'AuthorizationController@resetPasswordCheckPage')->name('resetPasswordCheckPage');

    Route::any('/wyloguj', 'AuthorizationController@logout')->name('logout');

});

//
//      AUTHED USER SITES
//

Route::group([], function () {
    
    Route::get('/ulubione', 'PanelController@favoritesPage')->name('favoritesPage');
    Route::get('/koszyk', 'PanelController@shoppingCartPage')->name('shoppingCartPage');

    Route::get('/zamowienie/tworzenie', 'PanelController@shoppingCartInformation')->name('shoppingCartInformation');
    Route::get('/zamowienie/{id}', 'PanelController@orderPage')->where('id', '[0-9]+')->name('orderIDPage');

    Route::get('/platnosc/status', 'PanelController@paymentStatus')->name('paymentStatusPage');

    Route::get('/platnosc/status/sukces', function() {
        return view('order.payments.success');
    })->name('paymentStatusPage-success');
});

//
//      PANEL SITES
//

Route::prefix('panel')->group(function () {

    Route::get('/', 'PanelController@dashboardPage')->name('panel_main');

    Route::get('/zamowienia', 'PanelController@ordersPage')->name('panel_orders');

    Route::get('/ustawienia', 'PanelController@settingsPage')->name('panel_settings');

    Route::get('/zgloszenia', 'PanelController@reportsPage')->name('panel_reports');
});

//
//      SYSTEM SITE
//

Route::prefix('systemSite')->group(function () {
    Route::post('handlePayU', 'SystemController@handlePayU')->name('systemSite_handlePayU');
});

//
//      SYSTEM - GENERAL
//

Route::prefix('system')->group(function () {

    //
    //      GENERAL
    //

    //
    //      AUTH
    //
    Route::post('signIn', 'AuthorizationController@signIn');
    Route::post('signUp', 'AuthorizationController@signUp');

    Route::post('activateAccountMail', 'AuthorizationController@activateAccountMail');

    Route::post('resetPasswordMail', 'AuthorizationController@resetPasswordMail');
    Route::post('resetPasswordChange', 'AuthorizationController@resetPasswordChange');
});

//
//      SYSTEM - AUTHED USER
//

Route::prefix('systemUser')->group(function () {

    Route::post('loadShoppingCartProducts', 'PanelSystemController@loadShoppingCartProducts');
    Route::post('addToShoppingCart', 'PanelSystemController@addProductToShoppingCart');
    Route::post('updateShoppingCart', 'PanelSystemController@updateShoppingCart');
    Route::post('confirmShoppingCart', 'PanelSystemController@confirmShoppingCart');

    Route::post('createOrder', 'PanelSystemController@createOrder');

    Route::post('paymentCancel', 'PanelSystemController@paymentCancel');
    Route::post('paymentPay', 'PanelSystemController@paymentPay');
    Route::post('paymentCheck', 'PanelSystemController@paymentCheck');


    Route::post('changeDataPersonal', 'PanelSystemController@changeDataPersonal');
    Route::post('changeDataLocation', 'PanelSystemController@changeDataLocation');
    Route::post('changePassword', 'PanelSystemController@changePassword');
});

//
//      ADMIN SITES
//

Route::prefix('admin')->group(function () {

    Route::get('not_authorizated', function () {
        return view('admin.not_authorizated');
    });

    Route::get('login', 'AdminAuthController@loginPage')->name('admin_loginPage');

    Route::get('panel', 'AdminController@dashboardPage')->name('admin_dashboardPage');

    Route::get('kategorie', 'AdminController@categoriesPage')->name('admin_categoriesPage');

    Route::get('produkty/lista', 'AdminController@productsListPage')->name('admin_productsListPage');
    Route::get('produkty/dodaj', 'AdminController@productsAddPage')->name('admin_productsAddPage');
    Route::get('produkty/edytuj/{id}', 'AdminController@productsEditPage')->name('admin_productsEditPage');

    Route::get('zamowienia', 'AdminController@ordersListPage')->name('admin_ordersListPage');
    Route::get('zamowienia/realizacja', 'AdminController@ordersRealisingListPage')->name('admin_ordersRealizeListPage');
    Route::get('zamowienia/{id}', 'AdminController@orderPage')->where('id', '[0-9]+')->name('admin_orderPageID');
    

    Route::get('uzytkownicy', 'AdminController@usersListPage')->name('admin_usersListPage');
    Route::get('uzytkownik', 'AdminController@userPage')->name('admin_userPage');

    Route::get('ustawienia', 'AdminController@settingsPage')->name('admin_settingsPage');
});

//
//      SYSTEM - ADMIN
//

Route::prefix('systemAdmin')->group(function () {

    //
    //      GENERAL
    //
    Route::post('categoryList', 'AdminSystemController@categoryList');
    Route::post('productLoadList', 'AdminSystemController@productLoadList');
    Route::post('dashboardData', 'AdminSystemController@dashboardData');

    //
    //      AUTH
    //
    Route::post('signIn', 'AdminAuthController@signIn');

    //
    //      CATEGORIES MANAGER SITES
    //
    Route::post('categoryAdd', 'AdminSystemController@categoryAdd');
    Route::post('categoryRemove', 'AdminSystemController@categoryRemove');
    Route::post('categoryEdit', 'AdminSystemController@categoryEdit');
    Route::post('categoryChangeOrder', 'AdminSystemController@categoryChangeOrder');

    //
    //      PRODUCT CREATE SITES
    //
    Route::post('productCreate', 'AdminSystemController@productCreate');
    Route::post('productAddImageUpload', 'AdminSystemController@productAddImageUpload');
    Route::post('productAddImageRemove', 'AdminSystemController@productAddImageRemove');
    Route::post('productLoadOldImages', 'AdminSystemController@productLoadOldImages');

    //
    //      PRODUCT EDIT SITES
    //
    Route::post('productLoadCurrent', 'AdminSystemController@productLoadCurrent');
    Route::post('productEdit', 'AdminSystemController@productEdit');
    Route::post('productEditImageList', 'AdminSystemController@productEditImageList');
    Route::post('productEditImageAdd', 'AdminSystemController@productEditImageAdd');
    Route::post('productEditImageRemove', 'AdminSystemController@productEditImageRemove');

    //
    //      ORDER MANAGE SITES
    //
    Route::post('orderChangeStatus', 'AdminSystemController@orderChangeStatus');
    Route::post('orderChangeDeliverLoc', 'AdminSystemController@orderChangeDeliverLoc');
    Route::post('orderChangePayment', 'AdminSystemController@orderChangePayment');
    Route::post('orderChangeCost', 'AdminSystemController@orderChangeCost');


    //
    //      USER MANAGE SITES
    //
    Route::post('userBan', 'AdminSystemController@userBan');
    Route::post('userUnban', 'AdminSystemController@userUnban');

    Route::post('userChangePersonal', 'AdminSystemController@userChangePersonal');
    Route::post('userChangeLocation', 'AdminSystemController@userChangeLocation');
    
    //
    //      SETTINGS SITES
    //
    Route::post('settingsMaintenanceChange', 'AdminSystemController@settingsMaintenanceChange');
    Route::post('settingsMaintenanceAddIP', 'AdminSystemController@settingsMaintenanceAddIP');
    Route::post('settingsMaintenanceDelIP', 'AdminSystemController@settingsMaintenanceDelIP');
});
