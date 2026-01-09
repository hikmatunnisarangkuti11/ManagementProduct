<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;


Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);