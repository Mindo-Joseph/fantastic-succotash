<?php

use App\Http\Controllers\Front\SearchController;
use App\Http\Controllers\Client\CMS\PageController;
use App\Http\Controllers\Client\CMS\EmailController;
use App\Http\Controllers\Client\SocialMediaController;
use App\Http\Controllers\Client\DownloadFileController;
use App\Http\Controllers\Client\Accounting\TaxController;
use App\Http\Controllers\Client\Accounting\OrderController;
use App\Http\Controllers\Client\Accounting\VendorController;
use App\Http\Controllers\Client\Accounting\LoyaltyController;
use App\Http\Controllers\Client\Accounting\PromoCodeController;

Route::get('email-test', function(){
    $details['email'] = 'pankaj@yopmail.com';
    dispatch(new App\Jobs\SendVerifyEmailJob($details))->delay(now()->addSeconds(2))->onQueue('course_interactions');
    dd('done');
});

Route::get('admin/login', 'Auth\LoginController@getClientLogin')->name('admin.login')->middleware('domain');
Route::get('file-download/{filename}', [DownloadFileController::class, 'index'])->name('file.download.index');
Route::post('admin/login/client', 'Auth\LoginController@clientLogin')->name('client.login');
Route::get('admin/wrong/url', 'Auth\LoginController@wrongurl')->name('wrong.client');

Route::group(['middleware' => ['ClientAuth','database'], 'prefix' => '/client'], function () {
    Route::any('/logout', 'Auth\LoginController@logout')->name('client.logout');
    Route::get('profile', 'Client\DashBoardController@profile')->name('client.profile');
    Route::get('dashboard', 'Client\DashBoardController@index')->name('client.dashboard');
    Route::get('salesInfo/monthly', 'Client\DashBoardController@monthlySalesInfo')->name('client.monthlySalesInfo');
    Route::get('salesInfo/yearly', 'Client\DashBoardController@yearlySalesInfo')->name('client.yearlySalesInfo');
    Route::get('salesInfo/weekly', 'Client\DashBoardController@weeklySalesInfo')->name('client.weeklySalesInfo');
    Route::get('categoryInfo', 'Client\DashBoardController@categoryInfo')->name('client.categoryInfo');
    Route::get('cms/pages', [PageController::class, 'index'])->name('cms.pages');
    Route::get('cms/page/{id}', [PageController::class, 'show'])->name('cms.page.show');
    Route::post('cms/page/update', [PageController::class, 'update'])->name('cms.page.update');
    Route::post('cms/page/create', [PageController::class, 'store'])->name('cms.page.create');
    Route::post('cms/page/delete', [PageController::class, 'destroy'])->name('cms.page.delete');
    Route::get('cms/emails', [EmailController::class, 'index'])->name('cms.emails');
    Route::get('account/orders', [OrderController::class, 'index'])->name('account.orders');
    Route::get('account/promo-code', [PromoCodeController::class, 'index'])->name('account.promo.code');
    Route::get('account/promo-code/filter', [PromoCodeController::class, 'filter'])->name('account.promo-code.filter');
    Route::get('account/promo-code/export', [PromoCodeController::class, 'export'])->name('account.promo-code.export');
    Route::get('social/media', [SocialMediaController::class, 'index'])->name('social.media.index');
    Route::post('social/media/create', [SocialMediaController::class, 'create'])->name('social.media.create');
    Route::post('social/media/update', [SocialMediaController::class, 'update'])->name('social.media.update');
    Route::get('social/media/edit', [SocialMediaController::class, 'edit'])->name('social.media.edit');
    Route::post('social/media/delete', [SocialMediaController::class, 'delete'])->name('social.media.delete');
    Route::get('account/loyalty', [LoyaltyController::class, 'index'])->name('account.loyalty');
    Route::get('account/tax', [TaxController::class, 'index'])->name('account.tax');
    Route::get('account/vendor', [VendorController::class, 'index'])->name('account.vendor');
    Route::get('account/tax/filter', [TaxController::class, 'filter'])->name('account.tax.filter');
    Route::get('account/tax/export', [TaxController::class, 'export'])->name('account.tax.export');
    Route::get('account/vendor/filter', [VendorController::class, 'filter'])->name('account.vendor.filter');
    Route::get('account/order/filter', [OrderController::class, 'filter'])->name('account.order.filter');
    Route::get('account/loyalty/filter', [LoyaltyController::class, 'filter'])->name('account.loyalty.filter');
    Route::get('account/loyalty/export', [LoyaltyController::class, 'export'])->name('account.loyalty.export');
    Route::get('account/order/export', [OrderController::class, 'export'])->name('account.order.export');
    Route::put('profile/{id}', 'Client\DashBoardController@updateProfile')->name('client.profile.update');
    Route::post('password/update', 'Client\DashBoardController@changePassword')->name('client.password.update');
    Route::get('configure', 'Client\ClientPreferenceController@index')->name('configure.index');
    Route::get('customize', 'Client\ClientPreferenceController@getCustomizePage')->name('configure.customize');
    Route::post('configUpdate/{code}', 'Client\ClientPreferenceController@update')->name('configure.update');
    Route::post('referandearnUpdate/{code}', 'Client\ClientPreferenceController@referandearnUpdate')->name('referandearn.update');
    Route::post('updateDomain/{code}', 'Client\ClientPreferenceController@postUpdateDomain')->name('client.updateDomain');
    Route::resource('banner', 'Client\BannerController');
    Route::post('banner/saveOrder', 'Client\BannerController@saveOrder');
    Route::post('banner/changeValidity', 'Client\BannerController@validity');
    Route::post('banner/toggle', 'Client\BannerController@toggleAllBanner')->name('banner.toggle');

    Route::get('web-styling', 'Client\WebStylingController@index')->name('webStyling.index');
    Route::post('web-styling/updateWebStyles', 'Client\WebStylingController@updateWebStyles')->name('styling.updateWebStyles');

    Route::get('app-styling', 'Client\AppStylingController@index')->name('appStyling.index');
    Route::post('app-styling/updateFont', 'Client\AppStylingController@updateFont')->name('styling.updateFont');
    Route::post('app-styling/updateColor', 'Client\AppStylingController@updateColor')->name('styling.updateColor');
    Route::post('app-styling/updateTabBar', 'Client\AppStylingController@updateTabBar')->name('styling.updateTabBar');
    Route::post('app-styling/updateHomePage', 'Client\AppStylingController@updateHomePage')->name('styling.updateHomePage');

    Route::resource('category', 'Client\CategoryController');
    Route::post('categoryOrder', 'Client\CategoryController@updateOrder')->name('category.order');
    Route::get('category/delete/{id}', 'Client\CategoryController@destroy');
    Route::resource('variant', 'Client\VariantController');
    Route::post('variant/order', 'Client\VariantController@updateOrders')->name('variant.order');
    Route::get('variant/cate/{cid}', 'Client\VariantController@variantbyCategory');
    Route::resource('brand', 'Client\BrandController');
    Route::post('brand/order', 'Client\BrandController@updateOrders')->name('brand.order');
    Route::resource('cms', 'Client\CmsController');
    Route::resource('tax', 'Client\TaxCategoryController');
    Route::resource('taxRate', 'Client\TaxRateController');
    Route::resource('addon', 'Client\AddonSetController');
    Route::resource('payment', 'Client\PaymentController');
    Route::resource('accounting', 'Client\AccountController');
    Route::get('vendor/filterdata', 'Client\VendorController@getFilterData')->name('vendor.filterdata');
    Route::resource('vendor', 'Client\VendorController');
    Route::get('vendor/categories/{id}', 'Client\VendorController@vendorCategory')->name('vendor.categories');
    Route::get('vendor/catalogs/{id}', 'Client\VendorController@vendorCatalog')->name('vendor.catalogs');
    Route::post('vendor/saveConfig/{id}', 'Client\VendorController@updateConfig')->name('vendor.config.update');
    Route::post('vendor/activeCategory/{id}', 'Client\VendorController@activeCategory')->name('vendor.category.update');
    Route::post('vendor/parentStatus/{id}', 'Client\VendorController@checkParentStatus')->name('category.parent.status');
    Route::get('calender/data/{id}', 'Client\VendorSlotController@returnJson')->name('vendor.calender.data');
    Route::post('vendor/slot/{id}', 'Client\VendorSlotController@store')->name('vendor.saveSlot');
    Route::post('vendor/updateSlot/{id}', 'Client\VendorSlotController@update')->name('vendor.updateSlot');
    Route::post('vendor/deleteSlot/{id}', 'Client\VendorSlotController@destroy')->name('vendor.deleteSlot');

    Route::post('vendor/importCSV', 'Client\VendorController@importCsv')->name('vendor.import');
    Route::post('vendor/serviceArea/{vid}', 'Client\ServiceAreaController@store')->name('vendor.serviceArea');
    Route::post('vendor/editArea/{vid}', 'Client\ServiceAreaController@edit')->name('vendor.serviceArea.edit');
    Route::post('vendor/updateArea/{id}', 'Client\ServiceAreaController@update');
    Route::post('vendor/deleteArea/{vid}', 'Client\ServiceAreaController@destroy')->name('vendor.serviceArea.delete');
    Route::resource('order', 'Client\OrderController');
    Route::post('orders/filter', 'Client\OrderController@postOrderFilter')->name('orders.filter');
    Route::get('order/return/{status}', 'Client\OrderController@returnOrders')->name('backend.order.returns');
    Route::get('order/return-modal/get-return-product-modal', 'Client\OrderController@getReturnProductModal')->name('get-return-product-modal');
    Route::post('order/update-product-return-client', 'Client\OrderController@updateProductReturn')->name('update.order.return.client');
    Route::get('order/{order_id}/{vendor_id}', 'Client\OrderController@getOrderDetail')->name('order.show.detail');
    Route::post('order/updateStatus', 'Client\OrderController@changeStatus')->name('order.changeStatus');
    Route::resource('customer', 'Client\UserController');
    Route::get('customer/account/{user}/{action}', 'Client\UserController@deleteCustomer')->name('customer.account.action');
    Route::get('customer/edit/{id}', 'Client\UserController@newEdit')->name('customer.new.edit');
    Route::put('newUpdate/edit/{id}', 'Client\UserController@newUpdate')->name('customer.new.update');
    Route::post('customer/change/status', 'Client\UserController@changeStatus')->name('customer.changeStatus');
    Route::resource('product', 'Client\ProductController');
    Route::post('product/importCSV', 'Client\ProductController@importCsv')->name('product.import');
    Route::post('product/validate', 'Client\ProductController@validateData')->name('product.validate');
    Route::get('product/add/{vendor_id}', 'Client\ProductController@create')->name('product.add');
    Route::post('product/getImages', 'Client\ProductController@getImages')->name('productImage.get');
    Route::post('product/deleteVariant', 'Client\ProductController@deleteVariant')->name('product.deleteVariant');
    Route::post('product/images', 'Client\ProductController@images')->name('product.images');
    Route::post('product/translation', 'Client\ProductController@translation')->name('product.translation');
    Route::post('product/variantRows', 'Client\ProductController@makeVariantRows')->name('product.makeRows');
    Route::post('product/variantImage/update', 'Client\ProductController@updateVariantImage')->name('product.variant.update');
    Route::get('product/image/delete/{pid}/{id}', 'Client\ProductController@deleteImage')->name('product.deleteImg');
    Route::resource('loyalty', 'Client\LoyaltyController');
    Route::post('loyalty/changeStatus', 'Client\LoyaltyController@changeStatus')->name('loyalty.changeStatus');
    Route::post('loyalty/getRedeemPoints', 'Client\LoyaltyController@getRedeemPoints')->name('loyalty.getRedeemPoints');
    Route::post('loyalty/setRedeemPoints', 'Client\LoyaltyController@setRedeemPoints')->name('loyalty.setRedeemPoints');
    Route::post('loyalty/setLoyaltyCheck', 'Client\LoyaltyController@setLoyaltyCheck')->name('loyalty.setLoyaltyCheck');
    Route::resource('celebrity', 'Client\CelebrityController');
    Route::post('celebrity/changeStatus', 'Client\CelebrityController@changeStatus')->name('celebrity.changeStatus');
    Route::post('celebrity/getBrands', 'Client\CelebrityController@getBrandList')->name('celebrity.getBrands');
    Route::resource('wallet', 'Client\WalletController');
    Route::resource('promocode', 'Client\PromocodeController');
    Route::resource('payoption', 'Client\PaymentOptionController');
    Route::post('updateAll', 'Client\PaymentOptionController@updateAll')->name('payoption.updateAll');
    Route::resource('inquiry', 'Client\ProductInquiryController');

    Route::get('subscriptions/users', 'Client\SubscriptionController@userSubscriptions')->name('subscriptions.users');
    Route::post('subscriptions/users/save/{slug?}', 'Client\SubscriptionController@saveUserSubscription')->name('subscriptions.saveUserSubscription');
    Route::get('subscriptions/users/edit/{slug}', 'Client\SubscriptionController@editUserSubscription')->name('subscriptions.editUserSubscription');
    Route::get('subscriptions/users/delete/{slug}', 'Client\SubscriptionController@deleteUserSubscription')->name('subscriptions.deleteUserSubscription');
    Route::post('subscriptions/users/updateStatus/{slug}', 'Client\SubscriptionController@updateUserSubscriptionStatus')->name('subscriptions.updateUserSubscriptionStatus');
    Route::get('subscriptions/vendors', 'Client\SubscriptionController@vendorSubscriptions')->name('subscriptions.vendors');
    Route::post('subscriptions/vendors/save/{slug?}', 'Client\SubscriptionController@saveVendorSubscription')->name('subscriptions.saveVendorSubscription');
    Route::get('subscriptions/vendors/edit/{slug}', 'Client\SubscriptionController@editVendorSubscription')->name('subscriptions.editVendorSubscription');
    Route::get('subscriptions/vendors/delete/{slug}', 'Client\SubscriptionController@deleteVendorSubscription')->name('subscriptions.deleteVendorSubscription');
    Route::post('subscriptions/vendors/updateStatus/{slug}', 'Client\SubscriptionController@updateVendorSubscriptionStatus')->name('subscriptions.updateVendorSubscriptionStatus');
    Route::post('subscriptions/vendors/updateOnRequest/{slug}', 'Client\SubscriptionController@updateVendorSubscriptionOnRequest')->name('subscriptions.updateVendorSubscriptionOnRequest');


    // pickup & delivery 
    Route::group(['prefix' => 'vendor/dispatcher'], function () {
        Route::post('updateCreateVendorInDispatch', 'Client\VendorController@updateCreateVendorInDispatch')->name('update.Create.Vendor.In.Dispatch');
    });
    

});


Route::get('/search11',[SearchController::class,'search']);

Route::group(['middleware' => 'auth:client', 'prefix' => '/admin'], function () {
    Route::get('/', 'Client\DashBoardController@index')->name('home');
    Route::get('{first}/{second}/{third}', 'Client\RoutingController@thirdLevel')->name('third');
    Route::get('{first}/{second}', 'Client\RoutingController@secondLevel')->name('second');
    Route::get('{any}', 'Client\RoutingController@root')->name('any');
});