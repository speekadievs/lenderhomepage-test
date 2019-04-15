<?php

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

/** Auth */
Route::post('login', 'Auth\LoginController@login')->name('auth.login');
Route::post('register', 'Auth\RegisterController@register')->name('auth.register');
Route::post('refresh-token', 'Auth\LoginController@refreshToken')->name('auth.refresh-token');

/** User */
Route::middleware('auth:api')->group(function () {
    Route::get('user', 'Api\UserController@index')->name('user');
    Route::put('user', 'Api\UserController@update')->name('user.update');

    Route::resource('teams', 'Api\TeamController')->except(['edit']);
    Route::resource('players', 'Api\PlayerController')->except(['edit']);
});

