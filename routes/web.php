<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

/* Route::get('/user', function () {
    return view('welcome');
}); */
include_once "images.php";
include_once "godpanel.php";

Route::domain('{domain}')->middleware(['subdomain'])->group(function() {
	include_once "frontend.php";
	include_once "backend.php";
	

    /*Route::get('/', function (Request $request) {
        echo "flskdfksdjfk";die;
    });*/
});

Route::get('showImg/{folder}/{img}',function($folder, $img){
	$image  = \Storage::disk('s3')->url($folder . '/' . $img);
	//$image  = storage_path('app/public/banner/'.$img);
    return \Image::make($image)->fit(460, 120)->response('jpg');
});

Route::get('/prods/{img}',function($img){
	$image  = \Storage::disk('s3')->url('prods/' . $img);
    return \Image::make($image)->fit(460, 320)->response('jpg');
});

/*Route::get('admin/login', function () {
    return view('auth/login');
})->name('admin.login');
Route::post('admin/login/client', 'Auth\LoginController@clientLogin')->name('client.login');
Route::get('admin/wrong/url','Auth\LoginController@wrongurl')->name('wrong.client');

    
});*/


	Route::domain('{domain}')->middleware(['subdomain'])->group(function() {
		include_once "frontend.php";
		include_once "backend.php";
	    /*Route::get('/', function (Request $request) {
	        echo "flskdfksdjfk";die;
	    });*/
	});

	/*Route::domain('{domain}')->middleware(['subdomain'])->group(function () {

		include_once "frontend.php";
		include_once "backend.php";
	    
	});*/

	Route::get('showImg/{folder}/{img}',function($folder, $img){
		$image  = \Storage::disk('s3')->url($folder . '/' . $img);
		//$image  = storage_path('app/public/banner/'.$img);
	    return \Image::make($image)->fit(460, 120)->response('jpg');
	});

	Route::get('/prods/{img}',function($img){
		$image  = \Storage::disk('s3')->url('prods/' . $img);
	    return \Image::make($image)->fit(460, 320)->response('jpg');
	});

	/*Route::get('admin/login', function () {
	    return view('auth/login');
	})->name('admin.login');
	Route::post('admin/login/client', 'Auth\LoginController@clientLogin')->name('client.login');
	Route::get('admin/wrong/url','Auth\LoginController@wrongurl')->name('wrong.client');*/

//});