<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('shops', [ShopController::class, 'index'])->name('shops.index');
    Route::get('shops/near/{postcode}', [ShopController::class, 'nearPostcode'])->name('shops.near.postcode');
    Route::get('shops/delivery/{postcode}', [ShopController::class, 'deliveryPostcode'])->name('shops.delivery.postcode');
    Route::post('shops', [ShopController::class, 'store'])->name('shops.store');
});
