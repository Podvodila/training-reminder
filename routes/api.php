<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function() {
    Route::post('/register', 'auth\AuthController@register')->name('auth.register');
    Route::post('/login', 'auth\AuthController@login')->name('auth.login');
    Route::post('/logout', 'auth\AuthController@logout')->name('auth.logout');
});

Route::middleware('auth:airlock')->group(function () {
    Route::get('/user', 'SinglePageController@getUser')->name('api.user');

    Route::group(['prefix' => 'exercises'], function() {
        Route::get('/', 'ExercisesController@index')->name('exercises.index');
        Route::get('/{exercise}', 'ExercisesController@show')->name('exercises.show');
        Route::post('/', 'ExercisesController@store')->name('exercises.store');
        Route::put('/{exercise}', 'ExercisesController@update')->name('exercises.update');
        Route::delete('/{exercise}', 'ExercisesController@destroy')->name('exercises.destroy');
    });

    Route::group(['prefix' => 'activities'], function() {
        Route::get('/', 'ActivitiesController@index')->name('activity.index');
        Route::get('/{activity}', 'ActivitiesController@show')->name('activity.show');
        Route::post('/', 'ActivitiesController@store')->name('activity.store');
        Route::put('/{activity}', 'ActivitiesController@update')->name('activity.update');
        Route::delete('/{activity}', 'ActivitiesController@destroy')->name('activity.destroy');
    });
});
