<?php
Route::get('admin/login', function () {
    return view('auth/login');
})->name('admin.login');

Route::post('admin/login/client', 'Auth\LoginController@clientLogin')->name('client.login');
Route::get('admin/wrong/url','Auth\LoginController@wrongurl')->name('wrong.client');

Route::group(['middleware' => ['auth:client', 'database'], 'prefix' => '/client'], function () {
        //Route::middleware('auth')->group(function () {
   
        Route::post('/logout', 'Auth\LoginController@logout')->name('client.logout');
        Route::get('profile','Client\DashBoardController@profile')->name('client.profile');
        Route::get('dashboard','Client\DashBoardController@index')->name('client.dashboard');
        Route::put('profile/{id}','Client\DashBoardController@updateProfile')->name('client.profile.update');
        Route::post('password/update','Client\DashBoardController@changePassword')->name('client.password.update');

        Route::get('configure','Client\ClientPreferenceController@index')->name('configure.index');
        Route::get('customize','Client\ClientPreferenceController@customize')->name('configure.customize');
        Route::post('configUpdate/{code}', 'Client\ClientPreferenceController@update')->name('configure.update');
        Route::post('updateDomain/{code}', 'Client\ClientPreferenceController@updateDomain')->name('client.updateDomain');
        //Route::post('configUpdate/{code}','Client\ClientPreferenceController@upDateConfig')->name('configure.index');
        Route::resource('banner','Client\BannerController');
        Route::post('banner/saveOrder','Client\BannerController@saveOrder');
        Route::post('banner/changeValidity','Client\BannerController@validity');

        Route::resource('category','Client\CategoryController');
        Route::post('categoryOrder','Client\CategoryController@updateOrder')->name('category.order');
        Route::get('category/delete/{id}','Client\CategoryController@destroy');
        Route::resource('variant','Client\VariantController');
        Route::post('variant/order','Client\VariantController@updateOrders')->name('variant.order');
        Route::get('variant/cate/{cid}','Client\VariantController@variantbyCategory');
        Route::resource('brand','Client\BrandController');
        Route::post('brand/order','Client\BrandController@updateOrders')->name('brand.order');

        Route::resource('promocode','Client\PromocodeController');
        Route::resource('cms','Client\CmsController');
        Route::resource('tax','Client\TaxCategoryController');
        Route::resource('taxRate','Client\TaxRateController');
        Route::resource('addon','Client\AddonSetController');
        Route::resource('payment','Client\PaymentController');
        Route::resource('accounting','Client\AccountController');

        Route::resource('vendor','Client\VendorController');
        Route::get('vendor/categories/{id}','Client\VendorController@vendorCategory')->name('vendor.categories');
        Route::get('vendor/catalogs/{id}','Client\VendorController@vendorCatalog')->name('vendor.catalogs');
        Route::get('vendor/categories/{id}','Client\VendorController@vendorCategory')->name('vendor.categories');
        Route::post('vendor/saveConfig/{id}','Client\VendorController@updateConfig')->name('vendor.config.update');
        
        Route::get('calender/data/{id}','Client\VendorSlotController@returnJson')->name('vendor.calender.data');
        Route::post('vendor/slot/{id}','Client\VendorSlotController@store')->name('vendor.saveSlot');
        Route::post('vendor/updateSlot/{id}','Client\VendorSlotController@update')->name('vendor.updateSlot');
        Route::post('vendor/deleteSlot/{id}','Client\VendorSlotController@destroy')->name('vendor.deleteSlot');

        Route::post('vendor/serviceArea/{vid}','Client\ServiceAreaController@store')->name('vendor.serviceArea');
        Route::post('vendor/editArea/{vid}','Client\ServiceAreaController@edit')->name('vendor.serviceArea.edit');
        Route::post('vendor/updateArea/{id}','Client\ServiceAreaController@update');
        Route::post('vendor/deleteArea/{vid}','Client\ServiceAreaController@destroy')->name('vendor.serviceArea.delete');

        Route::resource('order','Client\OrderController');
        Route::resource('customer','Client\UserController');
        Route::get('customer/account/{user}/{action}','Client\UserController@changeStatus')->name('customer.account.action');

        Route::resource('product','Client\ProductController');
        Route::post('product/validate','Client\ProductController@validateData')->name('product.validate');
        Route::get('product/add/{vendor_id}','Client\ProductController@create')->name('product.add');
        Route::post('product/getImages','Client\ProductController@getImages')->name('productImage.get');
        Route::post('product/deleteVariant','Client\ProductController@deleteVariant')->name('product.deleteVariant');
        Route::post('product/images','Client\ProductController@images')->name('product.images');
        Route::post('product/translation','Client\ProductController@translation')->name('product.translation');
        Route::post('product/variantRows','Client\ProductController@makeVariantRows')->name('product.makeRows');
        Route::post('product/variantImage/update','Client\ProductController@updateVariantImage')->name('product.variant.update');

        Route::resource('loyalty','Client\LoyaltyController');
        Route::post('loyalty/changeStatus','Client\LoyaltyController@changeStatus')->name('loyalty.changeStatus');
        Route::post('loyalty/getRedeemPoints','Client\LoyaltyController@getRedeemPoints')->name('loyalty.getRedeemPoints');
        Route::post('loyalty/setRedeemPoints','Client\LoyaltyController@setRedeemPoints')->name('loyalty.setRedeemPoints');

        Route::resource('promocode','Client\PromocodeController');

        Route::get('stripe/showForm','Client\PaymentController@showForm')->name('stripe.form');
        Route::post('stripe/make','Client\PaymentController@makePayment')->name('stripe.makePayment');

    });

    Route::group(['middleware' => 'auth:client', 'prefix' => '/admin'], function () {
        Route::get('/', 'Client\DashBoardController@index')->name('home');
        Route::get('{first}/{second}/{third}', 'Client\RoutingController@thirdLevel')->name('third');
        Route::get('{first}/{second}', 'Client\RoutingController@secondLevel')->name('second');
        Route::get('{any}', 'Client\RoutingController@root')->name('any');
    });