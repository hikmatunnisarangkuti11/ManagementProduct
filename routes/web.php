<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('product'))->name('product');
Route::get('/categories', fn() => view('category'))->name('category');
