<?php
/*
Route::domain('{subdomain}.myorder.com')->middleware(['subdomain', 'domain'])->group(function () {        
    Route::get('/','Front\UserhomeController@index')->name('userHome');
    Route::get('/productDetail/{id}','Front\ProductPageController@index')->name('productDetail');
});*/

Route::group(['middleware' => ['domain']], function () {

	Route::get('/','Front\UserhomeController@index')->name('userHome');
	Route::get('/product/{id}','Front\CatalogController@index')->name('productDetail');
	Route::get('category/{id?}', 'Front\CatalogController@categoryData')->name('categoryDetail');
    Route::get('vendor/{id?}', 'Front\CatalogController@productsByVendor')->name('vendorDetail');

    Route::post('primaryData', 'Front\UserhomeController@changePrimaryData')->name('changePrimaryData');

});