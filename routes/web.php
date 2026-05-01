<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// public routes
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    // products
    Route::resource('products', ProductController::class);

    // categories
    Route::resource('categories', CategoryController::class);

    //suppliers
    Route::resource('suppliers', SupplierController::class);

    //stock movements
    Route::resource('stock-movements', StockMovementController::class)
        ->only(['index', 'create', 'store', 'show']);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
