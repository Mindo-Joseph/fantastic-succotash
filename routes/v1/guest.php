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


        Route::get('category/{id?}', 'Api\v1\ProductController@categoryData');
        Route::get('category/filters/{id?}', 'Api\v1\ProductController@categoryFilter');
        

        Route::get('brand/{id?}', 'Api\v1\BrandController@productsByBrand');
        Route::get('brand/filters/{id?}', 'Api\v1\BrandController@brandFilter');

        Route::get('vendor/{id?}', 'Api\v1\VendorController@productsByVendor');
        Route::get('vendor/filters/{id?}', 'Api\v1\VendorController@vendorFilter');
        
        
    });

});