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

Route::post('');