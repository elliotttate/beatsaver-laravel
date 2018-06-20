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

Route::get('/', 'BeatSaverController@welcome')->name('home');

Route::get('/browse/downloads/{start?}', 'BeatSaverController@topDownloads')->name('browse.top.downloads');
Route::get('/browse/played/{start?}', 'BeatSaverController@topPlayed')->name('browse.top.played');
Route::get('/browse/newest/{start?}', 'BeatSaverController@newest')->name('browse.top.newest');

Route::get('/search', 'BeatSaverController@search')->name('search.form');
Route::post('/search', 'BeatSaverController@searchSubmit')->name('search.submit');

Route::get('/auth/login', 'UserController@login')->name('login.form');
Route::post('/auth/login', 'UserController@loginSubmit')->name('login.submit');
Route::get('/auth/register', 'UserController@register')->name('register.form');
Route::post('/auth/register', 'UserController@registerSubmit')->name('register.submit');
Route::get('/auth/forgotpw', 'UserController@forgotPw')->name('forgotpw.form');
Route::post('/auth/forgotpw', 'UserController@forgotPwSubmit')->name('forgotpw.submit');
Route::any('/auth/logout', 'UserController@logout')->name('logout');

Route::get('/legal/dmca', 'LegalController@dmca')->name('legal.dmca');
Route::get('/legal/privacy', 'LegalController@privacy')->name('legal.privacy');

