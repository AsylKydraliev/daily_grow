<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CounterpartyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReceiptController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/users', UserController::class);
    Route::resource('/branches', BranchController::class);
    Route::resource('/counterparties', CounterpartyController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/product-receipts', ProductReceiptController::class);
    Route::resource('/sales', SaleController::class);
    Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
    Route::get('/balance', [BalanceController::class, 'index'])->name('balance.index');
    Route::get('/balance/branches/{branch}/products', [BalanceController::class, 'branchProducts'])->name('balance.branch-products');
    Route::resource('/debts', DebtController::class);
});

require __DIR__.'/auth.php';
