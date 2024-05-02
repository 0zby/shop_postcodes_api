<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('shops', [ShopController::class, 'index']);
    Route::post('shops', [ShopController::class, 'store']);
});
