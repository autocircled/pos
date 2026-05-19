<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MfsController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Products
    Route::get('/products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
    Route::get('/products/{product}/fifo-batches', [ProductController::class, 'fifoBatches'])->name('products.fifo-batches');
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');

    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [POSController::class, 'searchProducts'])->name('pos.search');
    Route::get('/pos/product/{product}', [POSController::class, 'getProduct'])->name('pos.product');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{sale}', [POSController::class, 'receipt'])->name('pos.receipt');
    Route::get('/pos/history', [POSController::class, 'salesHistory'])->name('pos.history');
    Route::get('/pos/{sale}/edit', [POSController::class, 'edit'])->name('pos.edit');
    Route::put('/pos/{sale}', [POSController::class, 'update'])->name('pos.update');
    Route::post('/pos/{sale}/cancel', [POSController::class, 'cancelSale'])->name('pos.cancel');
    Route::get('/pos/due-payments', [POSController::class, 'duePayments'])->name('pos.due-payments');
    Route::post('/pos/{sale}/add-due-payment', [POSController::class, 'addDuePayment'])->name('pos.add-due-payment');

    // Reports
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit');

    // Profile (own password)
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Suppliers
    Route::resource('suppliers', SupplierController::class)->except(['show']);

    // Purchases
    Route::resource('purchases', PurchaseController::class)->except([]);
    Route::post('/purchases/{purchase}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive');
    Route::post('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
    Route::post('/purchases/{purchase}/pay', [PurchaseController::class, 'pay'])->name('purchases.pay');

    // Expenses
    Route::resource('expenses', ExpenseController::class)->except(['show']);

    // MFS (Mobile Financial Service)
    Route::get('/mfs', [MfsController::class, 'index'])->name('mfs.index');
    Route::post('/mfs/accounts', [MfsController::class, 'storeAccount'])->name('mfs.accounts.store');
    Route::put('/mfs/accounts/{account}', [MfsController::class, 'updateAccount'])->name('mfs.accounts.update');
    Route::delete('/mfs/accounts/{account}', [MfsController::class, 'destroyAccount'])->name('mfs.accounts.destroy');
    Route::get('/mfs/transactions', [MfsController::class, 'transactions'])->name('mfs.transactions');
    Route::post('/mfs/transactions', [MfsController::class, 'storeTransaction'])->name('mfs.transactions.store');
    Route::delete('/mfs/transactions/{transaction}', [MfsController::class, 'destroyTransaction'])->name('mfs.transactions.destroy');
    Route::get('/mfs/report', [MfsController::class, 'report'])->name('mfs.report');

    // User management, Activity log & Backups (admin only)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
        Route::get('/activity-log/{activity_log}', [ActivityLogController::class, 'show'])->name('activity-log.show');

        // Database backups
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups/run', [BackupController::class, 'run'])->name('backups.run');
        Route::get('/backups/{file}/download', [BackupController::class, 'download'])->name('backups.download');
        Route::post('/backups/{file}/restore', [BackupController::class, 'restore'])->name('backups.restore');
        Route::delete('/backups/{file}', [BackupController::class, 'destroy'])->name('backups.destroy');

        // Database migrations
        Route::get('/migrations', [BackupController::class, 'migrations'])->name('migrations.index');
        Route::post('/migrations/run', [BackupController::class, 'runMigration'])->name('migrations.run');
    });
});
