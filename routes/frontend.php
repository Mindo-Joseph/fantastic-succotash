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

	//Route::post('user/facebook/callback','Front\CustomerAuthController@fblogin');

	Route::post('validateEmail','Front\CustomerAuthController@validateEmail')->name('validateEmail');

	Route::post('user/loginData','Front\CustomerAuthController@login')->name('customer.loginData');
	Route::post('user/register','Front\CustomerAuthController@register')->name('customer.register');
	Route::post('user/forgotPassword','Front\CustomerAuthController@forgotPassword')->name('customer.forgotPass');
	Route::post('user/resetPassword','Front\CustomerAuthController@resetPassword')->name('customer.resetPass');
	
	Route::get('/','Front\UserhomeController@index')->name('userHome');
	Route::post('primaryData', 'Front\UserhomeController@changePrimaryData')->name('changePrimaryData');
	Route::post('paginateValue', 'Front\UserhomeController@changePaginate')->name('changePaginate');
	Route::get('/product/{id}','Front\ProductController@index')->name('productDetail');
	Route::post('/product/variant/{id}','Front\ProductController@getVariantData')->name('productVariant');
	

	Route::get('category/{id?}', 'Front\CategoryController@categoryProduct')->name('categoryDetail');
    Route::post('category/filters/{id}', 'Front\CategoryController@categoryFilters')->name('productFilters');

    Route::get('vendor/{id?}', 'Front\VendorController@vendorProducts')->name('vendorDetail');
    Route::post('vendor/filters/{id}', 'Front\VendorController@vendorFilters')->name('vendorProductFilters');

    Route::get('brand/{id?}', 'Front\BrandController@categoryProduct')->name('brandDetail');
    Route::post('brand/filters/{id}', 'Front\BrandController@categoryFilters')->name('brandProductFilters');

    /*Route::get('facebook', function () {
	    return view('facebook');
	});*/

	Route::get('auth/{driver}', 'Front\FacebookController@redirectToSocial');
	Route::get('auth/callback/{driver}', 'Front\FacebookController@handleSocialCallback');

	/*Route::get('auth/facebook', 'Front\FacebookController@redirectToFacebook');
	Route::get('auth/facebook/callback', 'Front\FacebookController@handleFacebookCallback');*/

	//Route::get('user/verify_account', 'Front\UserController@verifyAccount')->name('user.verify');


});

Route::group([
      'middleware' => ['domain', 'webAuth']
    ], function() {
        Route::get('user/verify_account', 'Front\UserController@verifyAccount')->name('user.verify');
		Route::get('user/profile', 'Front\UserController@profile')->name('user.profile');
		Route::get('sendToken/{id}', 'Front\UserController@sendToken')->name('verifyInfromation');
    });
