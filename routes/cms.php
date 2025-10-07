<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\ExportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
    Route::prefix('cms')->group(function() {
        Route::get('/dashboard', CMS\DashboardController::class)->name('cms.dashbord');
        
        Route::get('/reports/sales-by-product', [CMS\SalesReportController::class, 'salesByProduct'])->name('cms.salesByProduct');
        Route::get('/reports/sales-by-category', [CMS\SalesReportController::class, 'salesByCategory']);
        Route::get('/reports/products/{productId}/sales-trend', [CMS\SalesReportController::class, 'productSalesTrend']);
        Route::get('/reports/top-selling-products', [CMS\SalesReportController::class, 'topSellingProducts']);
    });
});