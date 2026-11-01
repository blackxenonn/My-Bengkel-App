<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;

// Halaman utama diarahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Halaman setelah login
Route::middleware('auth')->group(function () {

    // Dashboard untuk semua role yang sudah login
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Khusus Owner
    Route::middleware('role:owner')->group(function () {

        Route::get('/owner', function () {
            return 'Halaman khusus Owner';
        })->name('owner');

        // Manajemen kategori
        Route::resource('categories', CategoryController::class)
            ->except(['show']);

    });

    // Khusus Kasir
    Route::middleware('role:kasir')->group(function () {
        Route::get('/pos', function () {
            return 'Halaman POS Kasir';
        })->name('pos');
    });

    // Khusus Gudang
    Route::middleware('role:gudang')->group(function () {
        Route::get('/inventory', function () {
            return 'Halaman Inventaris Gudang';
        })->name('inventory');
    });

});
