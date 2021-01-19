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

Route::get('/banner/{img}',function($img){
	$image  = storage_path('app/public/banner/'.$img);
    return \Image::make($image)->fit(460, 120)->response('jpg');
});


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
	Route::put('profile/{id}','Client\DashBoardController@updateProfile')->name('client.profile.update');
	Route::post('password/update','Client\DashBoardController@changePassword')->name('client.password.update');

	Route::get('configure','Client\ClientPreferenceController@index')->name('configure.index');
	Route::get('customize','Client\ClientPreferenceController@customize')->name('configure.customize');
	Route::post('configUpdate/{code}', 'Client\ClientPreferenceController@update')->name('configure.update');
	Route::post('updateDomain/{code}', 'Client\ClientPreferenceController@updateDomain')->name('client.updateDomain');
	//Route::post('configUpdate/{code}','Client\ClientPreferenceController@upDateConfig')->name('configure.index');
	Route::resource('banner','Client\BannerController');
	Route::post('banner/saveOrder','Client\BannerController@saveOrder');
	Route::post('banner/changeValidity','Client\BannerController@validity');

	Route::resource('category','Client\CategoryController');
	Route::post('categoryOrder','Client\CategoryController@updateOrder')->name('category.order');
	Route::get('category/delete/{id}','Client\CategoryController@destroy');
	Route::resource('variant','Client\VariantController');
	Route::post('variant/order','Client\VariantController@updateOrders')->name('variant.order');
	Route::resource('brand','Client\BrandController');
	Route::post('brand/order','Client\BrandController@updateOrders')->name('brand.order');

	Route::resource('promocode','Client\PromocodeController');
	Route::resource('cms','Client\CmsController');
	Route::resource('tax','Client\TaxController');
	Route::resource('payment','Client\PaymentController');
	Route::resource('accounting','Client\AccountController');

	Route::resource('vendor','Client\VendorController');
	Route::get('vendor/categories/{id}','Client\VendorController@vendorCategory')->name('vendor.categories');
	Route::get('vendor/catalogs/{id}','Client\VendorController@vendorCatalog')->name('vendor.catalogs');
	Route::get('vendor/categories/{id}','Client\VendorController@vendorCategory')->name('vendor.categories');
	Route::post('vendor/saveConfig/{id}','Client\VendorController@updateConfig')->name('vendor.config.update');
	
	Route::get('calender/data/{id}','Client\VendorSlotController@returnJson')->name('vendor.calender.data');
	Route::post('vendor/slot/{id}','Client\VendorSlotController@store')->name('vendor.saveSlot');
	Route::post('vendor/updateSlot/{id}','Client\VendorSlotController@update')->name('vendor.updateSlot');
	Route::post('vendor/deleteSlot/{id}','Client\VendorSlotController@destroy')->name('vendor.deleteSlot');

	Route::post('vendor/serviceArea/{vid}','Client\ServiceAreaController@store')->name('vendor.serviceArea');
	Route::post('vendor/editArea/{vid}','Client\ServiceAreaController@edit')->name('vendor.serviceArea.edit');
	Route::post('vendor/updateArea/{id}','Client\ServiceAreaController@update');
	Route::post('vendor/deleteArea/{vid}','Client\ServiceAreaController@destroy')->name('vendor.serviceArea.delete');

	Route::resource('order','Client\OrderController');
	
});

Route::group(['middleware' => 'auth:client', 'prefix' => '/'], function () {

	Route::get('/', 'Client\DashBoardController@index')->name('home');
	Route::get('{first}/{second}/{third}', 'Client\RoutingController@thirdLevel')->name('third');
	Route::get('{first}/{second}', 'Client\RoutingController@secondLevel')->name('second');
	Route::get('{any}', 'Client\RoutingController@root')->name('any');
});



