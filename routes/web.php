<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();


/*		 GOD - PANEL 			*/
Route::group(['prefix' => '/godpanel'], function () {
	Route::get('login', function(){
		return view('godpanel/login');
	});
	Route::post('login','Godpanel\LoginController@Login')->name('god.login');

	Route::middleware('auth')->group(function () {
	
		Route::post('/logout', 'Godpanel\LoginController@logout')->name('god.logout');
		Route::get('dashboard','Godpanel\DashBoardController@index')->name('god.dashboard');
		Route::resource('language','Godpanel\LanguageController');
		Route::resource('currency','Godpanel\CurrencyController');
		Route::resource('map','Godpanel\MapProviderController');
		Route::resource('sms','Godpanel\SmsProviderController');
		Route::resource('client','Godpanel\ClientController');
		Route::get('map/destroy/{id}', 'Godpanel\MapProviderController@destroy');
		Route::get('sms/destroy/{id}', 'Godpanel\SmsProviderController@destroy');
	});
});

Route::group(['prefix' => '/godpanel'], function () {
	Route::get('login', function(){
		return view('godpanel/login');
	});
	Route::post('login','Godpanel\LoginController@Login')->name('god.login');

	Route::middleware(['middleware' => 'auth:admin'])->group(function () {
	
		Route::post('/logout', 'Godpanel\LoginController@logout')->name('god.logout');
		Route::get('dashboard','Godpanel\DashBoardController@index')->name('god.dashboard');
		Route::resource('language','Godpanel\LanguageController');
		Route::resource('currency','Godpanel\CurrencyController');
		Route::resource('map','Godpanel\MapProviderController');
		Route::resource('sms','Godpanel\SmsProviderController');
		Route::resource('client','Godpanel\ClientController');
		Route::get('map/destroy/{id}', 'Godpanel\MapProviderController@destroy');
		Route::get('sms/destroy/{id}', 'Godpanel\SmsProviderController@destroy');
	});
});

Route::post('/login/client', 'Auth\LoginController@clientLogin')->name('client.login');
Route::get('/wrong/url','Auth\LoginController@wrongurl')->name('wrong.client');

Route::group(['middleware' => ['auth:client', 'database'], 'prefix' => '/client'], function () {
//Route::group(['middleware' => ['auth:client'], 'prefix' => '/client'], function () {
	
	//Route::middleware('auth')->group(function () {

		//Route::get('index','Client\DashBoardController@index')->name('client.index');	
		Route::post('/logout', 'Auth\LoginController@logout')->name('client.logout');
		Route::post('/logout', 'Auth\LoginController@logout')->name('client.logout');
		Route::get('profile','Client\DashBoardController@profile')->name('client.profile');
		Route::get('dashboard','Client\DashBoardController@index')->name('client.dashboard');

		Route::get('configure','Client\ClientPreferenceController@index')->name('configure.index');
		Route::get('customize','Client\ClientPreferenceController@customize')->name('configure.customize');
		Route::post('configUpdate/{code}', 'Client\ClientPreferenceController@update')->name('configure.update');
		//Route::post('configUpdate/{code}','Client\ClientPreferenceController@upDateConfig')->name('configure.index');
		Route::resource('banner','Client\BannerController');
		Route::resource('promocode','Client\PromocodeController');
		Route::resource('cms','Client\CmsController');
		Route::resource('tax','Client\TaxController');
		Route::resource('payment','Client\PaymentController');
		Route::resource('accounting','Client\AccountController');




		
			
});

Route::group(['middleware' => 'auth:client', 'prefix' => '/'], function () {

	Route::get('/', 'Client\DashBoardController@index')->name('home');
	Route::get('{first}/{second}/{third}', 'Client\RoutingController@thirdLevel')->name('third');
	Route::get('{first}/{second}', 'Client\RoutingController@secondLevel')->name('second');
	Route::get('{any}', 'Client\RoutingController@root')->name('any');
});



