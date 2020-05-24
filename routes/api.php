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
    Route::post('/register', 'Auth\AuthController@register')->name('auth.register');
    Route::post('/login', 'Auth\AuthController@login')->name('auth.login');
    Route::post('/logout', 'Auth\AuthController@logout')->name('auth.logout');
});

Route::middleware('auth:airlock')->group(function () {
    Route::get('/user', 'SinglePageController@getUser')->name('api.user');

    Route::group(['prefix' => 'exercises'], function() {
        Route::get('/', 'ExercisesController@index')->name('exercises.index');
        Route::get('/get', 'ExercisesController@get')->name('exercises.get');
        Route::get('/{exercise}', 'ExercisesController@show')->name('exercises.show');
        Route::post('/', 'ExercisesController@store')->name('exercises.store');
        Route::put('/{exercise}', 'ExercisesController@update')->name('exercises.update');
        Route::delete('/{exercise}', 'ExercisesController@destroy')->name('exercises.destroy');
    });

    Route::group(['prefix' => 'activities'], function() {
        Route::get('/', 'ActivitiesController@index')->name('activities.index');
        Route::get('/all', 'ActivitiesController@get')->name('activities.get');
        Route::get('/{activity}', 'ActivitiesController@show')->name('activities.show');
        Route::post('/', 'ActivitiesController@store')->name('activities.store');
        Route::post('/toggle-status/{activity}', 'ActivitiesController@toggleStatus')->name('activities.toggle-status');
        Route::put('/{activity}', 'ActivitiesController@update')->name('activities.update');
        Route::delete('/{activity}', 'ActivitiesController@destroy')->name('activities.destroy');
    });

    Route::group(['prefix' => 'statistics'], function() {
        Route::get('/', 'StatisticsController@index')->name('statistics.index');
    });
});

Route::group(['prefix' => 'telegram'], function() {
    Route::post('/hook', 'TelegramBotController@hook')->name('telegram-bot.hook');
});
