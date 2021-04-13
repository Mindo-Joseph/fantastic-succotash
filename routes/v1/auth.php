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