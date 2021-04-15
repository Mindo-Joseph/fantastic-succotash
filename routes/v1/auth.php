<?php

Route::group([
    'prefix' => 'v1/auth'
], function () {

    Route::get('country-list', 'Api\v1\AuthController@countries');

    Route::group([
      'middleware' => ['dbCheck', 'AppAuth']
    ], function() {
        Route::post('sendToken', 'Api\v1\AuthController@sendToken');
        Route::get('logout', 'Api\v1\AuthController@logout');
    });
    Route::group([
      'middleware' => 'dbCheck'
    ], function() {
        //Route::post('sendOtp', 'Api\AuthController@sendOtp');
        Route::post('login', 'Api\v1\AuthController@login');
        Route::post('forgot_password', 'Api\v1\AuthController@forgotPassword');
        Route::post('reset_password', 'Api\v1\AuthController@resetPassword');
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
        Route::get('changePassword', 'Api\v1\ProfileController@changePassword')->name('user.changePassword');
    });
  
});