<?php

Route::get('/', 'BeatSaverController@welcome')->name('home');

Route::get('/browse/downloads/{start?}', 'BeatSaverController@topDownloads')->name('browse.top.downloads');
Route::get('/browse/played/{start?}', 'BeatSaverController@topPlayed')->name('browse.top.played');
Route::get('/browse/newest/{start?}', 'BeatSaverController@newest')->name('browse.top.newest');
Route::get('/browse/detail/{key}', 'BeatSaverController@detail')->name('browse.detail');
Route::get('/browse/byuser/{id}/{start?}', 'BeatSaverController@byUser')->name('browse.user');

Route::get('/download/{key}', 'BeatSaverController@download')->name('download');

Route::get('/search', 'BeatSaverController@search')->name('search.form');
Route::post('/search', 'BeatSaverController@searchSubmit')->name('search.submit');
Route::get('/search/{type}/{key}', 'BeatSaverController@searchResult')->name('search');

Route::get('/legal/dmca', 'LegalController@dmca')->name('legal.dmca');
Route::get('/legal/privacy', 'LegalController@privacy')->name('legal.privacy');

Route::group(['middleware' => ['auth']], function () {
    Route::post('/vote/{key}', 'BeatSaverController@vote')->name('votes.submit');

    Route::get('/upload', 'BeatSaverController@upload')->name('upload.form');
    Route::post('/upload', 'BeatSaverController@uploadSubmit')->name('upload.submit');

    Route::get('/profile', 'UserController@profile')->name('profile');

    Route::get('/auth/register/verify/{token}', 'UserController@verifyEmail')->name('register.verify');
    Route::post('/auth/register/verify/resend', 'UserController@verifyEmailResend')->name('register.verify.resend');
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('/auth/login', 'UserController@login')->name('login.form');
    Route::post('/auth/login', 'UserController@loginSubmit')->name('login.submit');

    Route::get('/auth/register', 'UserController@register')->name('register.form');
    Route::post('/auth/register', 'UserController@registerSubmit')->name('register.submit');

    Route::get('/auth/forgotpw', 'UserController@resetPassword')->name('password.reset.request.form');
    Route::post('/auth/forgotpw', 'UserController@resetPasswordSubmit')->name('password.reset.request.submit');

    Route::get('/auth/forgotpw/confirm/{token}', 'UserController@confirmPasswordReset')->name('password.reset.complete.form');
    Route::post('/auth/forgotpw/confirm', 'UserController@confirmPasswordResetSubmit')->name('password.reset.complete.submit');
});
Route::any('/auth/logout', 'UserController@logout')->name('logout');