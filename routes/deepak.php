<?php
Route::group(['middleware' => ['domain']], function () {

    Route::get('search','Front\SearchController@search');

});