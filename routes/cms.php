<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\ExportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function() {
    Route::prefix('cms')->group(function() {
        Route::get('/dashboard', CMS\DashboardController::class)->name('cms.dashbord');

        //route for transactions
        Route::controller(CMS\TransactionController::class)->group(function() {
            Route::get('/transactions', 'index')->name('cms.transactions');
            Route::delete('/transactions/{id}', 'destroy')->name('cms.destroy');
        });
        
        Route::get('/reports/sales-by-product', [CMS\SalesReportController::class, 'salesByProduct'])->name('cms.salesByProduct');
        Route::get('/reports/sales-by-category', [CMS\SalesReportController::class, 'salesByCategory']);
        Route::get('/reports/products/{productId}/sales-trend', [CMS\SalesReportController::class, 'productSalesTrend']);
        Route::get('/reports/top-selling-products', [CMS\SalesReportController::class, 'topSellingProducts']);

        //route for finance
        Route::controller(CMS\FinanceController::class)->group(function() {
            Route::get('/finance', 'index')->name('cms.finance');
        });

        //route for laporan
        Route::controller(CMS\LaporanController::class)->group(function() {
            Route::post('/laporan/store', 'store')->name('cms.laporan.store');
        });
    });
});