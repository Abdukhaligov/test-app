<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('/orders')->group(function () {
    Route::get('/{uuid}', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
});