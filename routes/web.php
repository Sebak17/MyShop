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

    Route::post('signIn', 'AuthorizationController@signIn')->name('system_signIn');
    Route::post('signUp', 'AuthorizationController@signUp')->name('system_signUp');

    Route::post('activateAccountMail', 'AuthorizationController@activateAccountMail')->name('system_activateAccountMail');

    Route::post('resetPasswordMail', 'AuthorizationController@resetPasswordMail')->name('system_resetPasswordMail');
    Route::post('resetPasswordChange', 'AuthorizationController@resetPasswordChange')->name('system_resetPasswordChange');
});

//
//      SYSTEM - AUTHED USER
//

Route::prefix('systemUser')->group(function () {

    Route::post('loadShoppingCartProducts', 'PanelSystemController@loadShoppingCartProducts')->name('systemUser_loadShoppingCartProducts');
    Route::post('addToShoppingCart', 'PanelSystemController@addProductToShoppingCart')->name('systemUser_addToShoppingCart');
    Route::post('updateShoppingCart', 'PanelSystemController@updateShoppingCart')->name('systemUser_updateShoppingCart');
    Route::post('confirmShoppingCart', 'PanelSystemController@confirmShoppingCart')->name('systemUser_confirmShoppingCart');

    Route::post('createOrder', 'PanelSystemController@createOrder')->name('systemUser_createOrder');


    Route::post('paymentCancel', 'PanelSystemController@paymentCancel')->name('systemUser_paymentCancel');
    Route::post('paymentPay', 'PanelSystemController@paymentPay')->name('systemUser_paymentPay');
    Route::post('paymentCheck', 'PanelSystemController@paymentCheck')->name('systemUser_paymentCheck');


    Route::post('changeDataPersonal', 'PanelSystemController@changeDataPersonal')->name('systemUser_changeDataPersonal');
    Route::post('changeDataLocation', 'PanelSystemController@changeDataLocation')->name('systemUser_changeDataLocation');
    Route::post('changePassword', 'PanelSystemController@changePassword')->name('systemUser_changePassword');

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




});

//
//      SYSTEM - ADMIN
//

Route::prefix('systemAdmin')->group(function () {

    //
    //      GENERAL
    //

    Route::post('categoryList', 'AdminSystemController@categoryList')->name('systemAdmin_categoryList');

    Route::post('productLoadList', 'AdminSystemController@productLoadList')->name('systemAdmin_productLoadList');

    Route::post('dashboardData', 'AdminSystemController@dashboardData')->name('systemAdmin_dashboardData');

    //
    //      AUTH
    //

    Route::post('signIn', 'AdminAuthController@signIn')->name('systemAdmin_signIn');

    //
    //      CATEGORIES MANAGER SITES
    //

    Route::post('categoryAdd', 'AdminSystemController@categoryAdd')->name('systemAdmin_categoryAdd');

    Route::post('categoryRemove', 'AdminSystemController@categoryRemove')->name('systemAdmin_categoryRemove');

    Route::post('categoryEdit', 'AdminSystemController@categoryEdit')->name('systemAdmin_categoryEdit');

    Route::post('categoryChangeOrder', 'AdminSystemController@categoryChangeOrder')->name('systemAdmin_categoryChangeOrder');

    //
    //      PRODUCT CREATE SITES
    //

    Route::post('productCreate', 'AdminSystemController@productCreate')->name('systemAdmin_productCreate');

    Route::post('productAddImageUpload', 'AdminSystemController@productAddImageUpload')->name('systemAdmin_productAddImageUpload');

    Route::post('productAddImageRemove', 'AdminSystemController@productAddImageRemove')->name('systemAdmin_productAddImageRemove');

    Route::post('productLoadOldImages', 'AdminSystemController@productLoadOldImages')->name('systemAdmin_productLoadOldImages');

    //
    //      PRODUCT EDIT SITES
    //

    Route::post('productLoadCurrent', 'AdminSystemController@productLoadCurrent')->name('systemAdmin_productLoadCurrent');

    Route::post('productEdit', 'AdminSystemController@productEdit')->name('systemAdmin_productEdit');

    Route::post('productEditImageList', 'AdminSystemController@productEditImageList')->name('systemAdmin_productEditImageList');

    Route::post('productEditImageAdd', 'AdminSystemController@productEditImageAdd')->name('systemAdmin_productEditImageAdd');

    Route::post('productEditImageRemove', 'AdminSystemController@productEditImageRemove')->name('systemAdmin_productEditImageRemove');

    //
    //      ORDER MANAGE SITES
    //
    
    Route::post('orderChangeStatus', 'AdminSystemController@orderChangeStatus')->name('systemAdmin_orderChangeStatus');
    Route::post('orderChangeDeliverLoc', 'AdminSystemController@orderChangeDeliverLoc')->name('systemAdmin_orderChangeDeliverLoc');
    Route::post('orderChangePayment', 'AdminSystemController@orderChangePayment')->name('systemAdmin_orderChangePayment');
    Route::post('orderChangeCost', 'AdminSystemController@orderChangeCost')->name('systemAdmin_orderChangeCost');


    //
    //      USER MANAGE SITES
    //
    
    Route::post('userBan', 'AdminSystemController@userBan')->name('systemAdmin_userBan');
    Route::post('userUnban', 'AdminSystemController@userUnban')->name('systemAdmin_userUnban');

    Route::post('userChangePersonal', 'AdminSystemController@userChangePersonal')->name('systemAdmin_userChangePersonal');
    Route::post('userChangeLocation', 'AdminSystemController@userChangeLocation')->name('systemAdmin_userChangeLocation');

});
