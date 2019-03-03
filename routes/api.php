<?php

use Illuminate\Http\Request;

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

Route::get('/songs/top/{start?}','ApiController@topDownloads');
Route::get('/songs/plays/{start?}','ApiController@topPlayed');
Route::get('/songs/new/{start?}','ApiController@newest');
Route::get('/songs/rated/{start?}','ApiController@topRated');
Route::get('/songs/byuser/{id}/{start?}','ApiController@byUser');
Route::get('/songs/detail/{key}','ApiController@detail');
Route::get('/songs/vote/{key}/{type}/{accessToken}', 'ApiController@vote');
Route::post('/songs/voteById/{key}/{type}', 'ApiController@voteById');
//Route::post('/songs/vote/{key}','ApiController@vote'); // @todo use post instead of get
Route::get('/songs/search/{type}/{key}','ApiController@search');
