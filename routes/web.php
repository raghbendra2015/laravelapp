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

Route::get('/', 'DashboardController@getDashboard');
Route::get('login', 'Auth\LoginController@getLogin');
Route::post('login', 'Auth\LoginController@postLogin');
Route::get('logout', 'Auth\LoginController@getLogout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register');

Route::get('dashboard', 'DashboardController@getDashboard');
Route::get('films', 'DashboardController@getFilmsList');
Route::get('films/add', 'DashboardController@AddFilm');
Route::post('films/save', 'DashboardController@saveFilm');
Route::get('films/{slug}', 'DashboardController@showFilm');
Route::post('films/save-comment', 'DashboardController@storeComment');


//Admin
Route::get('admin', 'AdminController@index');
Route::get('admin/film-list', 'AdminController@getFilmGridData');
Route::get('admin/add', 'AdminController@AddFilm');
Route::post('admin/save', 'AdminController@saveFilm');
Route::get('admin/edit/{id}', 'AdminController@getEditFilm');
Route::post('admin/update', 'AdminController@updateFilm');
Route::post('admin/delete', 'AdminController@deleteFilm');

});
