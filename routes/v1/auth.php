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
      'middleware' => ['dbCheck', 'AppAuth']
    ], function() {
        Route::get('profile', 'Api\v1\ProfileController@profile')->name('user.profile');
        Route::get('wishlists', 'Api\v1\ProfileController@wishlists')->name('user.wishlists');
        Route::get('wishlist/update/{pid?}', 'Api\v1\ProfileController@updateWishlist')->name('addWishlist');
        Route::get('addressBook/{id?}', 'Api\v1\ProfileController@addressBook')->name('user.addressBook');
        Route::get('orders', 'Api\v1\ProfileController@orders')->name('user.orders');
        Route::get('newsLetter', 'Api\v1\ProfileController@newsLetter')->name('user.newsLetter');
        Route::get('account', 'Api\v1\ProfileController@account')->name('user.account');
        Route::post('changePassword', 'Api\v1\ProfileController@changePassword')->name('user.changePassword');

        Route::post('update/image', 'Api\v1\ProfileController@updateAvatar')->name('user.avatar');
        Route::post('update/profile', 'Api\v1\ProfileController@updateProfile')->name('user.updateProfile');
        Route::post('user/address/{id?}', 'Api\v1\ProfileController@userAddress')->name('user.address');
    });
  
});