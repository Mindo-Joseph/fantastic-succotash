<!-- promocode routes
Route::group(['middleware' => ['auth:client', 'database'], 'prefix' => '/client'], function () {
Route::get('/create-promocodes', [PromoCodeController::class, 'create']);
Route::post('/store-promocode', [PromoCodeController::class, 'store'])->name('promocode.store');
Route::get('/showall-promocode', [PromoCodeController::class, 'index']);
Route::get('/edit-promocode/{id}', [PromoCodeController::class, 'edit']);
Route::post('/update', [PromoCodeController::class, 'update'])->name('promocode.update');
Route::get('/delete/{id}', [PromoCodeController::class, 'destroy']);
Route::get('/show/{id}', [PromoCodeController::class, 'show']);
}); -->