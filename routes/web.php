<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');

Route::group(['prefix' => 'auth'], function() {
    Route::post('/register', 'auth\AuthController@register')->name('auth.register');
    Route::post('/login', 'auth\AuthController@login')->name('auth.login');
    Route::post('/logout', 'auth\AuthController@logout')->name('auth.logout');
});
