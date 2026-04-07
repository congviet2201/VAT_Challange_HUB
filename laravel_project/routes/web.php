<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// vào trang chủ gọi index
Route::get('/', [HomeController::class, 'index'])->name('home');
// truy cập danh mục theo id
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');

