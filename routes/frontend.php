<?php
/*
Route::domain('{subdomain}.myorder.com')->middleware(['subdomain', 'domain'])->group(function () {        
    Route::get('/','Front\UserhomeController@index')->name('userHome');
    Route::get('/productDetail/{id}','Front\ProductPageController@index')->name('productDetail');
});*/

Route::group(['middleware' => ['domain']], function () {

	Route::get('user/login', [
		'as' => 'customer.login',
		'uses' => 'Front\CustomerAuthController@loginForm'
	]);

	Route::get('user/register', [
		'as' => 'customer.register',
		'uses' => 'Front\CustomerAuthController@registerForm'
	]);

	Route::get('user/forgotPassword', [
		'as' => 'customer.forgotPassword',
		'uses' => 'Front\CustomerAuthController@forgotPasswordForm'
	]);

	Route::get('user/resetPassword', [
		'as' => 'customer.resetPassword',
		'uses' => 'Front\CustomerAuthController@resetPasswordForm'
	]);

	Route::post('validateEmail','Front\CustomerAuthController@validateEmail')->name('validateEmail');

	Route::post('login','Front\CustomerAuthController@login')->name('user.login');
	Route::post('register','Front\CustomerAuthController@register')->name('user.register');
	Route::post('forgotPassword','Front\CustomerAuthController@forgotPassword')->name('user.forgotPass');
	Route::post('resetPassword','Front\CustomerAuthController@resetPassword')->name('user.resetPass');
	
	Route::get('/','Front\UserhomeController@index')->name('userHome');
	Route::post('primaryData', 'Front\UserhomeController@changePrimaryData')->name('changePrimaryData');
	Route::post('paginateValue', 'Front\UserhomeController@changePaginate')->name('changePaginate');
	Route::get('/product/{id}','Front\ProductController@index')->name('productDetail');
	Route::get('category/{id?}', 'Front\CategoryController@categoryProduct')->name('categoryDetail');
    Route::post('category/filters/{id}', 'Front\CategoryController@categoryFilters')->name('productFilters');
    Route::get('vendor/{id?}', 'Front\VendorController@vendorProducts')->name('vendorDetail');

});