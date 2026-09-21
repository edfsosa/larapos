<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\SaleController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return redirect(auth()->user()->homeRoute());
    })->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('pos', [PosController::class, 'index'])->name('pos.index');

    Route::prefix('api/pos')->group(function () {
        Route::get('products', [PosController::class, 'products']);
        Route::post('sales', [SaleController::class, 'store']);
    });
});

require __DIR__ . '/settings.php';
