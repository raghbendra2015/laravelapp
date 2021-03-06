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

Route::group(['middleware' => 'prevent-back-history'], function(){

Route::get('/', 'Auth\LoginController@getLogin');
Route::get('login', 'Auth\LoginController@getLogin');
Route::post('login', 'Auth\LoginController@postLogin');
Route::get('logout', 'Auth\LoginController@getLogout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register');

Route::get('dashboard', 'DashboardController@getDashboard');

});
