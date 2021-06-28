<?php

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
Route::group(['middleware' => ['domain']], function () {
	Route::get('dispatch-order-status-update/{id?}', 'Front\DispatcherController@dispatchOrderStatusUpdate')->name('dispatch-order-update'); // Order Status update Dispatch
	Route::get('demo', 'Front\CustomerAuthController@getTestHtmlPage');
	Route::post('payment/stripe', 'Front\StripeGatewayController@postPaymentViaStripe')->name('payment.stripe');
	Route::post('payment/paypal', 'Front\PaypalGatewayController@postPaymentViaPaypal')->name('payment.paypal');
	Route::get('payment/paypalSuccess', 'Front\PaypalGatewayController@paypalSuccess')->name('payment.paypalSuccess');
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
	Route::get('/search','Front\SearchController@search');
	Route::get('/','Front\UserhomeController@index')->name('userHome');
	Route::post('/homePageData','Front\UserhomeController@postHomePageData')->name('homePageData');
	Route::post('/homepage','Front\UserhomeController@homepage')->name('homepage');
	Route::post('validateEmail','Front\CustomerAuthController@validateEmail')->name('validateEmail');
	Route::post('user/loginData','Front\CustomerAuthController@login')->name('customer.loginData');
	Route::post('user/register','Front\CustomerAuthController@register')->name('customer.register');
	Route::post('user/forgotPassword','Front\CustomerAuthController@forgotPassword')->name('customer.forgotPass');
	Route::post('user/resetPassword','Front\CustomerAuthController@resetPassword')->name('customer.resetPass');
	Route::post('primaryData', 'Front\UserhomeController@changePrimaryData')->name('changePrimaryData');
	Route::post('paginateValue', 'Front\UserhomeController@changePaginate')->name('changePaginate');
	Route::get('/product/{id?}','Front\ProductController@index')->name('productDetail');
	Route::post('/product/variant/{id}','Front\ProductController@getVariantData')->name('productVariant');
	Route::post('add/product/cart','Front\CartController@postAddToCart')->name('addToCart');
	Route::post('add/product/prescription','Front\CartController@uploadPrescription')->name('cart.uploadPrescription');
	Route::get('cartProducts','Front\CartController@getCartData')->name('getCartProducts');
	Route::get('cartDetails','Front\CartController@getCartProducts')->name('cartDetails');
	Route::post('cartDelete','Front\CartController@emptyCartData')->name('emptyCartData');
	Route::post('/product/updateCartQuantity','Front\CartController@updateQuantity')->name('updateQuantity');
	Route::post('/product/deletecartproduct','Front\CartController@deleteCartProduct')->name('deleteCartProduct');
	Route::get('userAddress','Front\UserController@getUserAddress')->name('getUserAddress');
	Route::get('category/{id?}', 'Front\CategoryController@categoryProduct')->name('categoryDetail');
    Route::post('category/filters/{id}', 'Front\CategoryController@categoryFilters')->name('productFilters');
    Route::get('vendor/{id?}', 'Front\VendorController@vendorProducts')->name('vendorDetail');
    Route::post('vendor/filters/{id}', 'Front\VendorController@vendorFilters')->name('vendorProductFilters');
    Route::get('brand/{id?}', 'Front\BrandController@brandProducts')->name('brandDetail');
    Route::post('brand/filters/{id}', 'Front\BrandController@brandFilters')->name('brandProductFilters');
	Route::get('auth/{driver}', 'Front\FacebookController@redirectToSocial');
	Route::get('auth/callback/{driver}', 'Front\FacebookController@handleSocialCallback');
	Route::get('UserCheck', 'Front\UserController@checkUserLogin')->name('checkUserLogin');
	Route::get('stripe/showForm/{token}', 'Front\PaymentController@showFormApp')->name('stripe.formApp');
    Route::post('stripe/make', 'Front\PaymentController@makePayment')->name('stripe.makePayment');

});

Route::group(['middleware' => ['domain', 'webAuth']], function() {
	Route::get('viewcart','Front\CartController@showCart')->name('showCart');
	Route::get('user/orders', 'Front\OrderController@orders')->name('user.orders');
	Route::post('user/store', 'Front\AddressController@store')->name('address.store');
	Route::get('user/addAddress', 'Front\AddressController@add')->name('addNewAddress');
	Route::get('user/address/{id}', 'Front\AddressController@address')->name('user.address');
	Route::get('user/checkout', 'Front\UserController@checkout')->name('user.checkout');
    Route::get('user/profile', 'Front\ProfileController@profile')->name('user.profile');
    Route::get('user/logout', 'Front\CustomerAuthController@logout')->name('user.logout');
    Route::get('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');
	Route::get('user/editAddress/{id}', 'Front\AddressController@edit')->name('editAddress');
	Route::post('user/update/{id?}', 'Front\AddressController@update')->name('address.update');
    Route::get('user/wishlists', 'Front\WishlistController@wishlists')->name('user.wishlists');
	Route::post('verifyAccountProcess', 'Front\UserController@sendToken')->name('email.send');
    Route::post('sendToken/{id}', 'Front\UserController@sendToken')->name('verifyInformation');
	Route::post('user/placeorder', 'Front\OrderController@placeOrder')->name('user.placeorder');
    Route::get('user/newsLetter', 'Front\ProfileController@newsLetter')->name('user.newsLetter');
    Route::get('user/verify_account', 'Front\UserController@verifyAccount')->name('user.verify');
    Route::post('wishlist/update', 'Front\WishlistController@updateWishlist')->name('addWishlist');
	Route::post('verifTokenProcess', 'Front\UserController@verifyToken')->name('user.verifyToken');
    Route::get('user/addressBook', 'Front\AddressController@index')->name('user.addressBook');
	Route::get('user/wallet', 'Front\WalletController@index')->name('user.account');
	Route::get('user/deleteAddress/{id}', 'Front\AddressController@delete')->name('deleteAddress');
	Route::post('user/updateAccount', 'Front\ProfileController@updateAccount')->name('user.updateAccount');
    Route::get('user/editAccount', 'Front\ProfileController@editAccount')->name('user.editAccount');
	Route::get('user/sendRefferal', 'Front\ProfileController@showRefferal')->name('user.sendRefferal');
    Route::get('wishlist/remove/{sku}', 'Front\WishlistController@removeWishlist')->name('removeWishlist');
    Route::get('user/changePassword', 'Front\ProfileController@changePassword')->name('user.changePassword');
    Route::post('user/placeorder/make', 'Front\OrderController@makePayment')->name('placeorder.makePayment');
    Route::post('user/sendRefferalCode', 'Front\ProfileController@sendRefferalCode')->name('user.sendEmail');
    Route::get('user/resetSuccess','Front\CustomerAuthController@resetSuccess')->name('customer.resetSuccess');
	Route::post('verify/promocode', 'Front\PromoCodeController@postVerifyPromoCode')->name('verify.promocode');
	Route::post('remove/promocode', 'Front\PromoCodeController@postRemovePromoCode')->name('remove.promocode');
	Route::get('order/success/{order_id}', 'Front\OrderController@getOrderSuccessPage')->name('order.success');
	Route::post('promocode/list', 'Front\PromoCodeController@postPromoCodeList')->name('verify.promocode.list');
	Route::post('payment/option/list', 'Front\PaymentController@index')->name('payment.option.list');
	Route::get('user/setPrimaryAddress/{id}', 'Front\AddressController@setPrimaryAddress')->name('setPrimaryAddress');
	Route::post('user/submitPassword','Front\ProfileController@submitChangePassword')->name('user.submitChangePassword');
	Route::get('user/wallethistory','Front\WalletController@index')->name('user.wallet');
});