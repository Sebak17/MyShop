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

Route::any('/wyloguj', 'AuthorizationController@logout')->name('logout');

Route::get('/not_authorizated', function () {
	return view('auth.not_authorizated');
})->name('not_authorizated');

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
});
