<?php

Route::group([
    'prefix' => 'v1'
], function () {

    Route::group([
      'middleware' => ['dbCheck', 'checkAuth']
    ], function() {
        Route::post('homepage', 'Api\v1\HomeController@homepage');
        Route::post('header', 'Api\v1\HomeController@headerContent');
        Route::post('product', 'Api\v1\ProductController@productById');
        Route::post('get-products', 'Api\v1\ProductController@productList');
    });

});