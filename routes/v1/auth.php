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
        Route::post('myWallet/credit', 'Api\v1\WalletController@creditMyWallet');
        Route::post('store/revenue', 'Api\v1\StoreController@getMyStoreRevenueDetails');
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
        Route::get('payment/options/{page}', 'Api\v1\PaymentOptionController@getPaymentOptions');
        Route::get('payment/{gateway}', 'Api\v1\PaymentOptionController@postPayment');
        Route::post('payment/place/order', 'Api\v1\PaymentOptionController@postPlaceOrder');
        Route::get('user/loyalty/info', 'Api\v1\LoyaltyController@index');
        // Rating & review 
        Route::group(['prefix' => 'rating'], function () {
            Route::post('update-product-rating', 'Api\v1\RatingController@updateProductRating');
            Route::get('get-product-rating', 'Api\v1\RatingController@getProductRating');
        });
        Route::post('upload-file', 'Api\v1\RatingController@uploadFile');
         // Return order
        Route::group(['prefix' => 'return-order'], function () {
            Route::get('get-order-data-in-model', 'Api\v1\ReturnOrderController@getOrderDatainModel');
            Route::get('get-return-products', 'Api\v1\ReturnOrderController@getReturnProducts');
            Route::post('update-product-return', 'Api\v1\ReturnOrderController@updateProductReturn');
        });

        // pickup & delivery 
        Route::group(['prefix' => 'pickup-delivery'], function () {
            Route::post('get-list-of-vehicles-old/{id}', 'Api\v1\PickupDeliveryController@getListOfVehicles');
            Route::post('get-list-of-vehicles/{id}', 'Api\v1\PickupDeliveryController@productsByVendorInPickupDelivery');
            Route::post('create-order', 'Api\v1\PickupDeliveryController@createOrder');
            Route::post('cart/updateQuantity', 'Api\v1\CartController@updateQuantity');
            Route::post('promo-code/list', 'Api\v1\PickupDeliveryController@postPromoCodeList');
            Route::post('promo-code/verify', 'Api\v1\PickupDeliveryController@postVerifyPromoCode');
            Route::post('promo-code/remove', 'Api\v1\PickupDeliveryController@postRemovePromoCode');
            
        });

        // user subscription 
        Route::group(['prefix' => 'subscription'], function () {
            Route::get('plans/user', 'Api\v1\SubscriptionPlansUserController@getSubscriptionPlans');
            Route::post('plan/save/user/{slug?}', 'Api\v1\SubscriptionPlansUserController@saveSubscriptionPlan');
            Route::get('plan/edit/user/{slug}', 'Api\v1\SubscriptionPlansUserController@editSubscriptionPlan');
            Route::get('plan/delete/user/{slug}', 'Api\v1\SubscriptionPlansUserController@deleteSubscriptionPlan');
            Route::post('plan/updateStatus/user/{slug}', 'Api\v1\SubscriptionPlansUserController@updateSubscriptionPlanStatus');
        });
        Route::group(['prefix' => 'user/subscription'], function () {
            Route::get('plans', 'Api\v1\UserSubscriptionController@getSubscriptionPlans');
            Route::get('selectPlan/{slug}', 'Api\v1\UserSubscriptionController@selectSubscriptionPlan');
            Route::post('purchase/{slug}', 'Api\v1\UserSubscriptionController@purchaseSubscriptionPlan');
	        Route::post('cancel/{slug}', 'Api\v1\UserSubscriptionController@cancelSubscriptionPlan');
            Route::get('checkActivePlan/{slug}', 'Api\v1\UserSubscriptionController@checkActiveSubscriptionPlan');
        });

        // vendor subscription 
        Route::group(['prefix' => 'vendor/subscription'], function () {
            Route::get('plans/{id}', 'Api\v1\VendorSubscriptionController@getSubscriptionPlans');
            Route::get('select/{slug}', 'Api\v1\VendorSubscriptionController@selectSubscriptionPlan');
            Route::post('purchase/{id}/{slug}', 'Api\v1\VendorSubscriptionController@purchaseSubscriptionPlan');
            Route::post('cancel/{id}/{slug}', 'Api\v1\VendorSubscriptionController@cancelSubscriptionPlan');
            Route::get('checkActive/{id}/{slug}', 'Api\v1\VendorSubscriptionController@checkActiveSubscriptionPlan');
            Route::any('filterData', 'Api\v1\VendorSubscriptionController@getSubscriptionsFilterData');
            Route::post('status/update/{slug}', 'Api\v1\VendorSubscriptionController@updateSubscriptionStatus');
        }); 
        Route::group(['prefix' => 'subscription'], function () {
            Route::get('plans/vendor', 'Api\v1\SubscriptionPlansVendorController@getSubscriptionPlans');
            Route::post('plan/save/vendor/{slug?}', 'Api\v1\SubscriptionPlansVendorController@saveSubscriptionPlan');
            Route::get('plan/edit/vendor/{slug}', 'Api\v1\SubscriptionPlansVendorController@editSubscriptionPlan');
            Route::get('plan/delete/vendor/{slug}', 'Api\v1\SubscriptionPlansVendorController@deleteSubscriptionPlan');
            Route::post('plan/updateStatus/vendor/{slug}', 'Api\v1\SubscriptionPlansVendorController@updateSubscriptionPlanStatus');
            Route::post('plan/updateOnRequest/vendor/{slug}', 'Api\v1\SubscriptionPlansVendorController@updateSubscriptionPlanOnRequest');
        });
    });
});