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
Route::get('/browse/downloads/{start?}', 'BeatSaverController@topDownloads')->name('browse.top.donwloads');
Route::get('/browse/played/{start?}', 'BeatSaverController@topPlayed')->name('browse.top.played');
Route::get('/browse/newest/{start?}', 'BeatSaverController@newest')->name('browse.top.newest');
Route::get('/search', 'BeatSaverController@search')->name('search');
Route::get('/auth/login', 'AuthController@login')->name('login');
Route::get('/dmca', 'BeatSaverController@dmca')->name('dmca');

