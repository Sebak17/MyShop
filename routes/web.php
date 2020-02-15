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

    Route::get('/platnosc/status/payu', function() {
        if(isset($_GET['error']))
            return view('order.payments.error');
        else
            return view('order.payments.success');
    })->name('paymentStatusPage-success');
});

//
//      PANEL SITES
//

Route::prefix('panel')->middleware('auth:web')->group(function () {

    Route::get('/', 'PanelController@dashboardPage')->name('panel_main');

    Route::get('/zamowienia', 'PanelController@ordersPage')->name('panel_orders');

    Route::get('/ustawienia', 'PanelController@settingsPage')->name('panel_settings');
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

Route::prefix('systemUser')->middleware('auth:web')->group(function () {

    Route::post('changeFavoriteStatus', 'UserSystem\GeneralController@changeFavoriteStatus');

    Route::post('loadShoppingCartProducts', 'UserSystem\OrderController@loadShoppingCartProducts');
    Route::post('addToShoppingCart', 'UserSystem\OrderController@addProductToShoppingCart');
    Route::post('updateShoppingCart', 'UserSystem\OrderController@updateShoppingCart');
    Route::post('confirmShoppingCart', 'UserSystem\OrderController@confirmShoppingCart');

    Route::post('createOrder', 'UserSystem\OrderController@createOrder');

    Route::post('paymentCancel', 'UserSystem\PaymentController@paymentCancel');
    Route::post('paymentPay', 'UserSystem\PaymentController@paymentPay');
    Route::post('paymentCheck', 'UserSystem\PaymentController@paymentCheck');


    Route::post('changeDataPersonal', 'UserSystem\SettingsController@changeDataPersonal');
    Route::post('changeDataLocation', 'UserSystem\SettingsController@changeDataLocation');
    Route::post('changePassword', 'UserSystem\SettingsController@changePassword');
});

//
//      ADMIN UNAUTHED SITES
//

Route::prefix('admin')->group(function () {
    Route::get('login', 'AdminAuthController@loginPage')->name('admin_loginPage');

    Route::get('not_authorizated', function () {
        return view('admin.not_authorizated');
    });
});

//
//      ADMIN AUTHED SITES
//

Route::prefix('admin')->middleware('admin.auth')->group(function () {

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
    Route::post('signIn', 'AdminAuthController@signIn');
});

Route::prefix('systemAdmin')->middleware('admin.auth')->group(function () {

    //
    //      GENERAL
    //
    Route::post('categoryList', 'AdminSystem\GeneralController@categoryList');
    Route::post('productLoadList', 'AdminSystem\GeneralController@productLoadList');
    Route::post('dashboardData', 'AdminSystem\GeneralController@dashboardData');

    //
    //      CATEGORIES MANAGER SITES
    //
    Route::post('categoryAdd', 'AdminSystem\CategoryController@add');
    Route::post('categoryRemove', 'AdminSystem\CategoryController@remove');
    Route::post('categoryEdit', 'AdminSystem\CategoryController@edit');
    Route::post('categoryChangeOrder', 'AdminSystem\CategoryController@changeOrder');

    //
    //      PRODUCT CREATE SITES
    //
    Route::post('productCreate', 'AdminSystem\ProductCreateController@create');
    Route::post('productAddImageUpload', 'AdminSystem\ProductCreateController@imageUpload');
    Route::post('productAddImageRemove', 'AdminSystem\ProductCreateController@imageRemove');
    Route::post('productLoadOldImages', 'AdminSystem\ProductCreateController@loadOldImages');

    //
    //      PRODUCT EDIT SITES
    //
    Route::post('productLoadCurrent', 'AdminSystem\ProductEditController@loadCurrentData');
    Route::post('productEdit', 'AdminSystem\ProductEditController@edit');
    Route::post('productEditImageList', 'AdminSystem\ProductEditController@imageList');
    Route::post('productEditImageAdd', 'AdminSystem\ProductEditController@imageAdd');
    Route::post('productEditImageRemove', 'AdminSystem\ProductEditController@imageRemove');

    //
    //      ORDER MANAGE SITES
    //
    Route::post('orderChangeStatus', 'AdminSystem\OrderController@changeStatus');
    Route::post('orderChangeDeliverLoc', 'AdminSystem\OrderController@changeDeliverLoc');
    Route::post('orderChangePayment', 'AdminSystem\OrderController@changePayment');
    Route::post('orderChangeCost', 'AdminSystem\OrderController@changeCost');
    Route::post('orderChangeParcelID', 'AdminSystem\OrderController@changeParcelID');


    //
    //      USER MANAGE SITES
    //
    Route::post('userBan', 'AdminSystem\UserController@ban');
    Route::post('userUnban', 'AdminSystem\UserController@unban');

    Route::post('userChangePersonal', 'AdminSystem\UserController@changePersonal');
    Route::post('userChangeLocation', 'AdminSystem\UserController@changeLocation');
    
    //
    //      SETTINGS SITES
    //
    Route::post('settingsMaintenanceChange', 'AdminSystem\SettingsController@maintenanceChange');
    Route::post('settingsMaintenanceAddIP', 'AdminSystem\SettingsController@maintenanceAddIP');
    Route::post('settingsMaintenanceDelIP', 'AdminSystem\SettingsController@maintenanceDelIP');
});
