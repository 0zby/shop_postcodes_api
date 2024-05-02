<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('shops', [ShopController::class, 'index']);
    Route::get('shops/near/{postcode}', [ShopController::class, 'nearPostcode']);
    Route::get('shops/delivery/{postcode}', [ShopController::class, 'deliveryPostcode']);
    Route::post('shops', [ShopController::class, 'store']);
});
