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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/logowanie', 'AuthorizationController@loginPage')->name('loginPage');
Route::get('/rejestracja', 'AuthorizationController@registerPage')->name('registerPage');

Route::get('/aktywuj_konto', 'AuthorizationController@activeAccountPage')->name('activeAccountPage');

Route::get('/aktywuj_konto/{hash}', 'AuthorizationController@activeAccountCheckPage')->name('activeAccountCheckPage');

Route::get('/resetuj_haslo', 'AuthorizationController@resetPasswordPage')->name('resetPasswordPage');

Route::get('/resetuj_haslo/{hash}', 'AuthorizationController@resetPasswordCheckPage')->name('resetPasswordCheckPage');

Route::any('/wyloguj', 'AuthorizationController@logout')->name('logout');

Route::get('/not_authorizated', function () {
	return view('auth.not_authorizated');
})->name('not_authorizated');



Route::any('/oferty', 'OffersController@offersPage')->name('offersPage');



Route::prefix('panel')->group(function () {

    Route::get('/', 'PanelController@dashboardPage')->name('panel_main');

    Route::get('/ustawienia', 'PanelController@settingsPage')->name('panel_settings');
});


Route::prefix('system')->group(function () {

    Route::post('signIn', 'AuthorizationController@signIn')->name('system_signIn');
    Route::get('signIn', function () {
        return redirect('/');
    });

    Route::post('signUp', 'AuthorizationController@signUp')->name('system_signUp');
    Route::get('signUp', function () {
        return redirect('/');
    });

    Route::post('categoriesList', 'HomeController@loadCategories')->name('system_categoriesList');
    Route::get('categoriesList', function () {
        return redirect('/');
    });

    Route::post('activateAccountMail', 'AuthorizationController@activateAccountMail')->name('system_activateAccountMail');
    Route::get('activateAccountMail', function () {
        return redirect('/');
    });

    Route::post('resetPasswordMail', 'AuthorizationController@resetPasswordMail')->name('system_resetPasswordMail');
    Route::get('resetPasswordMail', function () {
        return redirect('/');
    });

    Route::post('resetPasswordChange', 'AuthorizationController@resetPasswordChange')->name('system_resetPasswordChange');
    Route::get('resetPasswordChange', function () {
        return redirect('/');
    });
});

Route::prefix('systemUser')->group(function () {

    Route::post('changeDataPersonal', 'PanelSystemController@changeDataPersonal')->name('systemUser_changeDataPersonal');
    Route::get('changeDataPersonal', function () {
        return redirect('/');
    });

    Route::post('changeDataLocation', 'PanelSystemController@changeDataLocation')->name('systemUser_changeDataLocation');
    Route::get('changeDataLocation', function () {
        return redirect('/');
    });

    Route::post('changePassword', 'PanelSystemController@changePassword')->name('systemUser_changePassword');
    Route::get('changePassword', function () {
        return redirect('/');
    });

});

Route::prefix('admin')->group(function () {

    Route::get('/', function () {
        return redirect('/');
    });

    Route::get('not_authorizated', function () {
        return view('admin.not_authorizated');
    });

    Route::get('login', 'AdminAuthController@loginPage')->name('admin_loginPage');

    Route::get('panel', 'AdminController@dashboardPage')->name('admin_dashboardPage');

    Route::get('kategorie', 'AdminController@categoriesPage')->name('admin_categoriesPage');

    Route::get('produkty/lista', 'AdminController@productsListPage')->name('admin_productsListPage');
    Route::get('produkty/dodaj', 'AdminController@productsAddPage')->name('admin_productsAddPage');
    Route::get('produkty/edytuj/{id}', 'AdminController@productsEditPage')->name('admin_productsEditPage');
});

Route::prefix('systemAdmin')->group(function () {

    Route::post('signIn', 'AdminAuthController@signIn')->name('systemAdmin_signIn');
    Route::get('signIn', function () {
        return redirect('/');
    });

    Route::post('categoryList', 'AdminSystemController@categoryList')->name('systemAdmin_categoryList');
    Route::get('categoryList', function () {
        return redirect('/');
    });

    Route::post('categoryAdd', 'AdminSystemController@categoryAdd')->name('systemAdmin_categoryAdd');
    Route::get('categoryAdd', function () {
        return redirect('/');
    });

    Route::post('categoryRemove', 'AdminSystemController@categoryRemove')->name('systemAdmin_categoryRemove');
    Route::get('categoryRemove', function () {
        return redirect('/');
    });

    Route::post('categoryChangeOrder', 'AdminSystemController@categoryChangeOrder')->name('systemAdmin_categoryChangeOrder');
    Route::get('categoryChangeOrder', function () {
        return redirect('/');
    });

    Route::post('productLoadList', 'AdminSystemController@productLoadList')->name('systemAdmin_productLoadList');
    Route::get('productLoadList', function () {
        return redirect('/');
    });

    Route::post('productCreate', 'AdminSystemController@productCreate')->name('systemAdmin_productCreate');
    Route::get('productCreate', function () {
        return redirect('/');
    });

    Route::post('productAddImageUpload', 'AdminSystemController@productAddImageUpload')->name('systemAdmin_productAddImageUpload');
    Route::get('productAddImageUpload', function () {
        return redirect('/');
    });


    Route::post('productAddImageRemove', 'AdminSystemController@productAddImageRemove')->name('systemAdmin_productAddImageRemove');
    Route::get('productAddImageRemove', function () {
        return redirect('/');
    });

    Route::post('productLoadOldImages', 'AdminSystemController@productLoadOldImages')->name('systemAdmin_productLoadOldImages');
    Route::get('productLoadOldImages', function () {
        return redirect('/');
    });

    Route::post('productLoadCurrent', 'AdminSystemController@productLoadCurrent')->name('systemAdmin_productLoadCurrent');
    Route::get('productLoadCurrent', function () {
        return redirect('/');
    });

    Route::post('productEdit', 'AdminSystemController@productEdit')->name('systemAdmin_productEdit');
    Route::get('productEdit', function () {
        return redirect('/');
    });

    Route::post('productEditImageList', 'AdminSystemController@productEditImageList')->name('systemAdmin_productEditImageList');
    Route::get('productEditImageList', function () {
        return redirect('/');
    });

    Route::post('productEditImageAdd', 'AdminSystemController@productEditImageAdd')->name('systemAdmin_productEditImageAdd');
    Route::get('productEditImageAdd', function () {
        return redirect('/');
    });

    Route::post('productEditImageRemove', 'AdminSystemController@productEditImageRemove')->name('systemAdmin_productEditImageRemove');
    Route::get('productEditImageRemove', function () {
        return redirect('/');
    });

});