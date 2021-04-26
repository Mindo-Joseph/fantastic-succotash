<?php

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
		Route::get('delete/client/{id}', 'Godpanel\ClientController@remove');
	});
});
