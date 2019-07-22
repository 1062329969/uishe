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
Route::middleware(['auth'])->group(function () {

    //用户
    Route::get('/user/index', 'UserController@index')->name('user');
    Route::get('/loginout', 'UserController@loginout')->name('loginout');
});

Route::get('/', 'HomeController@index');
Route::any('/login', 'HomeController@login')->name('login');


