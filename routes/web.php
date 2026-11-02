<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CancellationRequestController;


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

        Route::get('/owner', [OwnerController::class, 'index'])->name('owner');

        // Manajemen kategori
        Route::resource('categories', CategoryController::class)
            ->except(['show']);
        
        //Manajemen Produk
        Route::resource('products', ProductController::class)
            ->except(['show']);
        
        //Manajemen Karyawan
        Route::resource('employees', EmployeeController::class)
            ->except(['show']);
        
        //Manajemen Laporan
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');
        
        Route::post(
            '/transactions/{transaction}/cancel',
            [TransactionController::class, 'cancel']
        )->name('transactions.cancel');

        Route::get(
            '/cancellation-requests',
            [CancellationRequestController::class, 'index']
        )->name('cancellation-requests.index');

    });

    // Khusus Kasir
    Route::middleware('role:kasir')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos');

        Route::post('/pos/checkout', [PosController::class, 'checkout'])
            ->name('pos.checkout');
        
        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');

        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
            ->name('transactions.show');
        
        // Pengajuan pembatalan oleh kasir
        Route::post(
            '/transactions/{transaction}/request-cancellation',
            [TransactionController::class, 'requestCancellation']
        )->name('transactions.request-cancellation');
    });

    // Khusus Gudang
    Route::middleware('role:gudang')->group(function () {
        Route::get('/inventory', function () {
            return 'Halaman Inventaris Gudang';
        })->name('inventory');

        Route::get('/stock-movements', [StockMovementController::class, 'index'])
            ->name('stock-movements.index');

        Route::get('/stock-movements/create', [StockMovementController::class, 'create'])
            ->name('stock-movements.create');

        Route::post('/stock-movements', [StockMovementController::class, 'store'])
            ->name('stock-movements.store');
    });

});
