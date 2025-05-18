<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'products' => ProductController::class,
    'customers' => CustomerController::class,
]);
