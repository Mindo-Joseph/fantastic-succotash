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
	Route::post('/product/cart','Front\CartController@addToCart')->name('addToCart');
	Route::get('cartProducts','Front\CartController@getCartData')->name('getCartProducts');
	Route::get('viewcart','Front\CartController@showCart')->name('showCart');
	Route::post('/product/updateCartQuantity','Front\CartController@updateQuantity')->name('updateQuantity');
	Route::post('/product/deletecartproduct','Front\CartController@deleteCartProduct')->name('deleteCartProduct');
	Route::get('userAddress','Front\UserController@getUserAddress')->name('getUserAddress');

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


	Route::get('UserCheck', 'Front\UserController@checkUserLogin')->name('checkUserLogin');

	Route::get('stripe/showForm/{token}', 'Front\PaymentController@showFormApp')->name('stripe.formApp');
    Route::post('stripe/make', 'Front\PaymentController@makePayment')->name('stripe.makePayment');

	/*Route::get('auth/facebook', 'Front\FacebookController@redirectToFacebook');
	Route::get('auth/facebook/callback', 'Front\FacebookController@handleFacebookCallback');*/

	//Route::get('user/verify_account', 'Front\UserController@verifyAccount')->name('user.verify');

	Route::get('user/cart/ding/dong/ping/pong', 'Front\CartController@getCartData')->name('user.dingPong');

});

Route::group(['middleware' => ['domain', 'webAuth']], function() {
    Route::get('user/verify_account', 'Front\UserController@verifyAccount')->name('user.verify');
    Route::post('sendToken/{id}', 'Front\UserController@sendToken')->name('verifyInformation');
    Route::get('user/resetSuccess','Front\CustomerAuthController@resetSuccess')->name('customer.resetSuccess');

    Route::get('user/profile', 'Front\ProfileController@profile')->name('user.profile');
    Route::get('user/wishlists', 'Front\ProfileController@wishlists')->name('user.wishlists');
    Route::post('wishlist/update', 'Front\ProfileController@updateWishlist')->name('addWishlist');

    Route::get('user/addressBook', 'Front\ProfileController@addresBook')->name('user.addressBook');
	Route::get('user/setPrimaryAddress/{id}', 'Front\AddressController@setPrimaryAddress')->name('setPrimaryAddress');
	Route::get('user/deleteAddress/{id}', 'Front\AddressController@delete')->name('deleteAddress');
	Route::get('user/editAddress/{id}', 'Front\AddressController@edit')->name('editAddress');
	Route::get('user/addAddress', 'Front\AddressController@add')->name('addNewAddress');
	Route::post('user/store', 'Front\AddressController@store')->name('address.store');
	Route::post('user/update/{id}', 'Front\AddressController@update')->name('address.update');

    Route::get('user/orders', 'Front\ProfileController@orders')->name('user.orders');
    Route::get('user/newsLetter', 'Front\ProfileController@newsLetter')->name('user.newsLetter');
    Route::get('user/editAccount', 'Front\ProfileController@editAccount')->name('user.editAccount');
    Route::get('user/changePassword', 'Front\ProfileController@changePassword')->name('user.changePassword');
    Route::get('user/logout', 'Front\CustomerAuthController@logout')->name('user.logout');
    Route::get('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');

	Route::post('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');
	Route::post('verifTokenProcess', 'Front\UserController@verifyToken')->name('user.verifyToken');

	Route::get('user/checkout', 'Front\UserController@checkout')->name('user.checkout');

	Route::post('user/placeorder', 'Front\OrderController@placeOrder')->name('user.placeorder');

	// Route::post('user/placeorder/showForm', 'Front\OrderController@showFormApp')->name('placeorder.formApp');
    Route::post('user/placeorder/make', 'Front\OrderController@makePayment')->name('placeorder.makePayment');
});