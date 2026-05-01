<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// public routes
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    //dashboard
    Route::get('/dashboard', function() {
        return inertia('Dashboard');
    })->name('dashboard');

    // products
    Route::resource('products', ProductController::class);

    // categories
    Route::resource('categories', CategoryController::class);
}); 

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
