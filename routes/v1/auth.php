<?php

Route::group([
    'prefix' => 'v1/auth'
], function () {

    Route::get('country-list', 'Api\v1\AuthController@countries');

    Route::group([
      'middleware' => ['dbCheck', 'AppAuth']
    ], function() {
        Route::post('sendToken', 'Api\v1\AuthController@sendToken');
        Route::post('verifyAccount', 'Api\v1\AuthController@verifyToken');
        Route::get('logout', 'Api\v1\AuthController@logout');
    });
    Route::group([
      'middleware' => 'dbCheck'
    ], function() {
        //Route::post('sendOtp', 'Api\AuthController@sendOtp');
        Route::post('login', 'Api\v1\AuthController@login');
        Route::post('forgotPassword', 'Api\v1\AuthController@forgotPassword');
        Route::post('resetPassword', 'Api\v1\AuthController@resetPassword');
        Route::post('register', 'Api\v1\AuthController@signup');
        //Route::get('cmscontent','Api\ActivityController@cmsData');

        Route::post('register', 'Api\v1\AuthController@signup');
    });

});

Route::group([
    'prefix' => 'v1'
], function () {

    Route::group([
      'middleware' => 'dbCheck'
    ], function() {
        Route::post('social/info', 'Api\v1\SocialController@getKeys');
        Route::post('social/login/{driver}', 'Api\v1\SocialController@login');
    });


    Route::group([
      'middleware' => ['dbCheck', 'AppAuth']
    ], function() {

        Route::get('profile', 'Api\v1\ProfileController@profile');
        Route::get('wishlists', 'Api\v1\ProfileController@wishlists');
        Route::get('wishlist/update/{pid?}', 'Api\v1\ProfileController@updateWishlist');
        Route::get('addressBook/{id?}', 'Api\v1\ProfileController@addressBook');
        Route::get('orders', 'Api\v1\ProfileController@orders');
        Route::get('newsLetter', 'Api\v1\ProfileController@newsLetter');
        Route::get('account', 'Api\v1\ProfileController@account');
        Route::post('changePassword', 'Api\v1\ProfileController@changePassword');

        Route::post('update/image', 'Api\v1\ProfileController@updateAvatar');
        Route::post('update/profile', 'Api\v1\ProfileController@updateProfile');
        Route::post('user/getAddress', 'Api\v1\ProfileController@getAddress');
        Route::get('primary/address/{id}', 'Api\v1\ProfileController@primaryAddress');
        Route::get('delete/address/{id}', 'Api\v1\ProfileController@deleteAddress');

        Route::post('user/address/{id?}', 'Api\v1\ProfileController@userAddress');
    });
  
});