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

Route::get('/', "HomeController@index");


// Route::get('/parse', "ParseController@parse");

Route::group(['middleware' => 'auth'], function () {
	Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
	Route::get('/home', 'HomeController@index')->name('home');
});


Auth::routes(['register' => false]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {

	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');

	Route::get('/test', function () {
		$res = DB::table('statistics')->where('app_version', '3.0.0')->first();
		return response()->json($res);
	});
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
	Route::get('country_summary', 'CountrySummaryController@index')->name('country_summary');
	Route::get('country_summary/get', 'CountrySummaryController@getSummary')->name('country_summary.get');
	Route::post('country_summary/get/hourly', 'CountrySummaryController@getHourlySummary')->name('country_summary.hourly');
	Route::get("country_summary/get/country_list", "CountrySummaryController@getCountryList")->name('country_summary.country_list');
	Route::get("country_summary/get/country_list/weekly", "CountrySummaryController@getCountryListWeekly")->name('country_summary.country_list.weekly');



	Route::get('app_summary', "AppSummaryController@index")->name('app_summary');
	Route::get('app_summary/get', 'AppSummaryController@getSummary')->name('app_summary.get');
	Route::post('app_summary/get/filter', 'AppSummaryController@getFilterSummmary')->name('app_summary.filter');
	Route::get("app_summary/get/country_list", "AppSummaryController@getCountryList")->name('app_summary.country_list');
});