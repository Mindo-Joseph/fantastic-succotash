<?php

Route::group(['middleware' => ['admin']], function () {
    Route::namespace('Webkul\Webfont\Http\Controllers')->group(function () {
        Route::get('admin/webfont', 'WebfontController@index')
            ->name('admin.cms.webfont');

        Route::get('admin/webfont/add', 'WebfontController@add')
            ->name('admin.cms.webfont.add');

        Route::post('admin/webfont/add', 'WebfontController@store')
            ->name('admin.cms.webfont.store');

        Route::get('admin/webfont/activate/{id}', 'WebfontController@activate')
            ->name('admin.cms.webfont.activate');

        Route::post('admin/webfont/remove/{id}', 'WebfontController@remove')
            ->name('admin.cms.webfont.remove');

        Route::post('admin/webfont/massupdate', 'WebfontController@massupdate')
            ->name('admin.cms.webfont.massupdate');

        Route::post('admin/webfont/massdelete', 'WebfontController@massDelete')
            ->name('admin.cms.webfont.massdelete');
    });
});
