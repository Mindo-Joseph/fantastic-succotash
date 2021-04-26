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
	Route::post('/product/cart','Front\ProductController@addToCart')->name('addToCart');
	Route::get('cartProducts','Front\ProductController@getCartProducts')->name('getCartProducts');
	Route::get('viewcart','Front\ProductController@showCart')->name('showCart');
	Route::post('/product/updateCartQuantity','Front\ProductController@updateQuantity')->name('updateQuantity');
	Route::post('/product/deletecartproduct','Front\ProductController@deleteCartProduct')->name('deleteCartProduct');

	Route::get('category/{id?}', 'Front\CategoryController@categoryProduct')->name('categoryDetail');
    Route::post('category/filters/{id}', 'Front\CategoryController@categoryFilters')->name('productFilters');

    Route::get('vendor/{id?}', 'Front\VendorController@vendorProducts')->name('vendorDetail');
    Route::post('vendor/filters/{id}', 'Front\VendorController@vendorFilters')->name('vendorProductFilters');

    Route::get('brand/{id?}', 'Front\BrandController@brandProducts')->name('brandDetail');
    Route::post('brand/filters/{id}', 'Front\BrandController@brandFilters')->name('brandProductFilters');

    /*Route::get('facebook', function () {
	    return view('facebook');
	});*/

	Route::get('auth/{driver}', 'Front\FacebookController@redirectToSocial');
	Route::get('auth/callback/{driver}', 'Front\FacebookController@handleSocialCallback');

	/*Route::get('auth/facebook', 'Front\FacebookController@redirectToFacebook');
	Route::get('auth/facebook/callback', 'Front\FacebookController@handleFacebookCallback');*/

	//Route::get('user/verify_account', 'Front\UserController@verifyAccount')->name('user.verify');


});

Route::group(['middleware' => ['domain', 'webAuth']], function() {
    Route::get('user/verify_account', 'Front\UserController@verifyAccount')->name('user.verify');
    Route::post('sendToken/{id}', 'Front\UserController@sendToken')->name('verifyInformation');
    Route::get('user/resetSuccess','Front\CustomerAuthController@resetSuccess')->name('customer.resetSuccess');

    Route::get('user/profile', 'Front\ProfileController@profile')->name('user.profile');
    Route::get('user/wishlists', 'Front\ProfileController@wishlists')->name('user.wishlists');
    Route::post('wishlist/update', 'Front\ProfileController@updateWishlist')->name('addWishlist');
    Route::get('user/addressBook', 'Front\ProfileController@addresBook')->name('user.addressBook');
    Route::get('user/orders', 'Front\ProfileController@orders')->name('user.orders');
    Route::get('user/newsLetter', 'Front\ProfileController@newsLetter')->name('user.newsLetter');
    Route::get('user/editAccount', 'Front\ProfileController@editAccount')->name('user.editAccount');
    Route::get('user/changePassword', 'Front\ProfileController@changePassword')->name('user.changePassword');
    Route::get('user/logout', 'Front\ProfileController@logout')->name('user.logout');
    Route::get('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');

	Route::post('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');
	Route::post('verifTokenProcess', 'Front\UserController@verifyToken')->name('user.verifyToken');

});