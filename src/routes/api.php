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
});

/** Films */
Route::get('films', 'Api\FilmController@index')->name('films.index');
Route::post('films', 'Api\FilmController@store')->name('films.store');
Route::get('films/{id}', 'Api\FilmController@show')->name('films.show');
Route::put('films/{film}', 'Api\FilmController@update')->name('films.update');
Route::delete('films/{film}', 'Api\FilmController@destroy')->name('films.destroy');

/** Comments */
Route::middleware('auth:api')->post('comments', 'Api\CommentController@store')->name('comments.store');

/** Genres */
Route::get('genres', 'Api\GenreController@index')->name('genres.index');
Route::middleware(['auth:api', 'auth.is_admin'])->group(function () {
    Route::post('genres', 'Api\GenreController@store')->name('genres.store');
    Route::put('genres/{genre}', 'Api\GenreController@update')->name('genres.update');
    Route::delete('genres/{genre}', 'Api\GenreController@destroy')->name('genres.destroy');
});

/** Countries */
Route::get('countries', 'Api\CountryController@index')->name('countries.index');
