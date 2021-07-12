<?php
Route::group(['prefix' => 'v1/auth'], function () {
    Route::get('country-list', 'Api\v1\AuthController@countries');
    Route::group(['middleware' => ['dbCheck', 'AppAuth', 'apilogger']], function() {
        Route::get('logout', 'Api\v1\AuthController@logout');
        Route::post('sendToken', 'Api\v1\AuthController@sendToken');
        Route::post('verifyAccount', 'Api\v1\AuthController@verifyToken');
    });
    Route::group(['middleware' => ['dbCheck', 'apilogger']], function() {
        Route::post('login', 'Api\v1\AuthController@login');
        Route::post('register', 'Api\v1\AuthController@signup');
        Route::post('resetPassword', 'Api\v1\AuthController@resetPassword');
        Route::post('forgotPassword', 'Api\v1\AuthController@forgotPassword');
    });
});
Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => ['dbCheck', 'apilogger']], function() {
        Route::post('social/info', 'Api\v1\SocialController@getKeys');
        Route::post('social/login/{driver}', 'Api\v1\SocialController@login');
    });
    Route::group(['middleware' => ['dbCheck', 'AppAuth', 'apilogger']], function() {
        Route::get('profile', 'Api\v1\ProfileController@profile');
        Route::get('account', 'Api\v1\ProfileController@account');
        Route::get('orders', 'Api\v1\OrderController@getOrdersList');
        Route::get('wishlists', 'Api\v1\ProfileController@wishlists');
        Route::get('newsLetter', 'Api\v1\ProfileController@newsLetter');
        Route::get('mystore', 'Api\v1\StoreController@getMyStoreDetails');
        Route::post('place/order', 'Api\v1\OrderController@postPlaceOrder');
        Route::post('update/image', 'Api\v1\ProfileController@updateAvatar');
        Route::post('user/getAddress', 'Api\v1\ProfileController@getAddress');
        Route::post('order-detail', 'Api\v1\OrderController@postOrderDetail');
        Route::post('update/profile', 'Api\v1\ProfileController@updateProfile');
        Route::get('myWallet', 'Api\v1\WalletController@getFindMyWalletDetails');
        Route::get('payment/options', 'Api\v1\PaymentOptionController@getPaymentOptions');
        Route::post('changePassword', 'Api\v1\ProfileController@changePassword');
        Route::get('addressBook/{id?}', 'Api\v1\AddressController@getAddressList');
        Route::get('revenue-details', 'Api\v1\RevenueController@getRevenueDetails');
        Route::post('user/address/{id?}', 'Api\v1\AddressController@postSaveAddress');
        Route::get('delete/address/{id}', 'Api\v1\AddressController@postDeleteAddress');
        Route::get('wishlist/update/{pid?}', 'Api\v1\ProfileController@updateWishlist');
        Route::post('send/referralcode', 'Api\v1\ProfileController@postSendReffralCode');
        Route::get('mystore/product/list', 'Api\v1\StoreController@getMyStoreProductList');
        Route::get('primary/address/{id}', 'Api\v1\AddressController@postUpdatePrimaryAddress');
        Route::post('update/order/status', 'Api\v1\OrderController@postVendorOrderStatusUpdate');
        // Rating & review 
        Route::group(['prefix' => 'rating'], function () {
            Route::post('update-product-rating', 'Api\v1\RatingController@updateProductRating');
            Route::get('get-product-rating', 'Api\v1\RatingController@getProductRating');
        });
        // pickup & delivery 
        Route::group(['prefix' => 'pickup-delivery'], function () {
            Route::post('get-list-of-vehicles-old/{id}', 'Api\v1\PickupDeliveryController@getListOfVehicles');
            Route::post('get-list-of-vehicles/{id}', 'Api\v1\PickupDeliveryController@productsByVendorInPickupDelivery');
            Route::post('create-order', 'Api\v1\PickupDeliveryController@createOrder');
            
        });
    });
});