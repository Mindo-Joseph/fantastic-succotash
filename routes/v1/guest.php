<?php
Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => ['dbCheck', 'checkAuth', 'apilogger']], function() {
        Route::post('cart/add', 'Api\v1\CartController@add');
        Route::get('cart/list', 'Api\v1\CartController@index');
        Route::post('homepage', 'Api\v1\HomeController@homepage');
        Route::post('header', 'Api\v1\HomeController@headerContent');
        Route::get('product/{id}', 'Api\v1\ProductController@productById');
        Route::post('get-products', 'Api\v1\ProductController@productList');
        Route::get('brand/{id?}', 'Api\v1\BrandController@productsByBrand');
        Route::get('category/{id?}', 'Api\v1\CategoryController@categoryData');
        Route::get('vendor/{id?}', 'Api\v1\VendorController@productsByVendor');
        Route::post('search/{type}/{id?}', 'Api\v1\HomeController@globalSearch');
        Route::post('brand/filters/{id?}', 'Api\v1\BrandController@brandFilters');
        Route::get('celebrity/{all?}', 'Api\v1\CelebrityController@celebrityList');
        Route::post('vendor/filters/{id?}', 'Api\v1\VendorController@vendorFilters');
        Route::post('category/filters/{id?}', 'Api\v1\CategoryController@categoryFilters');
        Route::get('celebrityProducts/{id?}', 'Api\v1\CelebrityController@celebrityProducts');
        Route::post('celebrity/filters/{id?}', 'Api\v1\CelebrityController@celebrityFilters');
        Route::post('productByVariant/{id}','Api\v1\ProductController@getVariantData')->name('productVariant');
    });
    Route::group(['middleware' => ['dbCheck','systemAuth', 'apilogger']], function() {
        Route::get('cart/empty', 'Api\v1\CartController@emptyCart');
        Route::get('coupons/{id?}', 'Api\v1\CouponController@list');
        Route::post('cart/remove', 'Api\v1\CartController@removeItem');
        Route::get('cart/totalItems', 'Api\v1\CartController@getItemCount');
        Route::post('cart/updateQuantity', 'Api\v1\CartController@updateQuantity');
        Route::post('promo-code/list', 'Api\v1\PromoCodeController@postPromoCodeList');
        Route::post('promo-code/verify', 'Api\v1\PromoCodeController@postVerifyPromoCode');
        Route::post('promo-code/remove', 'Api\v1\PromoCodeController@postRemovePromoCode');
        Route::post('vendor/category/list', 'Api\v1\VendorController@postVendorCategoryList');
    });
});