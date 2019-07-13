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

Route::get('/', function () {
	return view('welcome');
});


/**
 * Google Auth
 */

Route::get('checkAuth', 'SocialAuthGoogleController@checkAuth')->name('google.checkAuth');
Route::get('checkDb', 'PublicController@checkdb')->name('checkdb');


Route::group(['prefix' => 'google'], function () {
	Route::get('redirect', 'SocialAuthGoogleController@redirect')->name('google.auth');
	Route::get('callback', 'SocialAuthGoogleController@callback')->name('google.callback');
});


/*-------------------------------------------------------------------------
	Authed route
--------------------------------------------------------------------------*/

Route::group(['middleware' => ['checkAuth']], function () {

	/*------------------------------------------------------------------
		Followup functions
	-------------------------------------------------------------------*/

	// Create new followup
	Route::post('followup', 'FollowupController@postFollowup')->name('followup.create');
	Route::get('test', 'PublicController@getTest')->name('test');

	// Dashboard
	Route::get('dashboard', 'MemberController@getDashboard')->name('dashboard');
});


Auth::routes();
