<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockAlertController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProductImportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

// Login hanya untuk pengguna yang belum login.
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.store');
});

// Logout hanya untuk pengguna yang sudah login.
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Route yang dapat diakses Admin dan Staff.
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/', function () {
            return redirect()->route('dashboard');
        });
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


        Route::get('/products/import/template', [ProductImportController::class, 'template',])->name('products.import.template');
        Route::get('/products/import', [ProductImportController::class, 'create',])->name('products.import.create');
        Route::post('/products/import', [ProductImportController::class, 'store',])->name('products.import.store');

        // Manajemen produk.
        Route::resource('products', ProductController::class);

        // Peringatan stok minimum.
        Route::get('/stock-alerts', [StockAlertController::class, 'index'])->name('stock-alerts.index');

        // Laporan ringkasan aktivitas.
        Route::get('/reports/activity-summary', [ReportController::class, 'activitySummary'])->name('reports.activity-summary');
        Route::get('/reports/activity-summary/export', [ReportController::class, 'exportActivitySummary'])->name('reports.activity-summary.export');

        // Transaksi stok.
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [StockTransactionController::class, 'index'])->name('index');
            Route::get('/in/create', [StockTransactionController::class, 'createIn'])->name('in.create');
            Route::post('/in', [StockTransactionController::class, 'storeIn'])->name('in.store');
            Route::get('/out/create', [StockTransactionController::class, 'createOut'])->name('out.create');
            Route::post('/out', [StockTransactionController::class, 'storeOut'])->name('out.store');
            Route::get('/adjustments/create', [StockTransactionController::class, 'createAdjustment'])->name('adjustments.create');
            Route::post('/adjustments', [StockTransactionController::class, 'storeAdjustment'])->name('adjustments.store');
        });

        // Laporan stok dan transaksi.
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
            Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
            Route::get('/stock/export', [ReportController::class, 'exportStock'])->name('stock.export');
            Route::get('/transactions/export', [ReportController::class, 'exportTransactions'])->name('transactions.export');
        });


        // Fitur khusus Admin.
        Route::middleware('role:admin')->group(function () {

            // Manajemen kategori dan supplier.
            Route::resource('categories', CategoryController::class);
            Route::resource('suppliers', SupplierController::class);
            // Manajemen pengguna.
            Route::resource('users', UserManagementController::class)->except(['show', 'destroy']);
            Route::patch('/users/{user}/status', [UserManagementController::class, 'toggleStatus',])->name('users.toggle-status');
            // Riwayat aktivitas sistem.
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        });
    });
});
