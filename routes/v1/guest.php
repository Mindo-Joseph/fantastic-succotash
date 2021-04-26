<?php

Route::group([
    'prefix' => 'v1'
], function () {

    Route::group([
      'middleware' => ['dbCheck', 'checkAuth']
    ], function() {
        Route::post('homepage', 'Api\v1\HomeController@homepage');
        Route::post('header', 'Api\v1\HomeController@headerContent');
        Route::get('product/{id}', 'Api\v1\ProductController@productById');
        Route::post('get-products', 'Api\v1\ProductController@productList');


        Route::get('category/{id?}', 'Api\v1\CategoryController@categoryData');
        Route::post('category/filters/{id?}', 'Api\v1\CategoryController@categoryFilters');
        

        Route::get('brand/{id?}', 'Api\v1\BrandController@productsByBrand');
        Route::post('brand/filters/{id?}', 'Api\v1\BrandController@brandFilters');

        Route::get('vendor/{id?}', 'Api\v1\VendorController@productsByVendor');
        Route::post('vendor/filters/{id?}', 'Api\v1\VendorController@vendorFilters');
    });


    Route::group([
      'middleware' => ['dbCheck', 'systemAuth']
    ], function() {
        Route::get('cart/list', 'Api\v1\CartController@index');
        Route::post('cart/add', 'Api\v1\CartController@add');
        Route::get('cart/remove', 'Api\v1\CartController@remove');
        Route::post('cart/updateQuantity', 'Api\v1\CartController@updateQuantity');

    });

});