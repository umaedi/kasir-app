<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::view('/', 'login.index')->name('login');

//route for login
Route::controller(AuthController::class)->group(function() {
    Route::post('/login', 'login')->name('login-post');
});

//route for kasir
Route::middleware(['auth', 'role:kasir,admin'])->group(function () {
    Route::get('/kasir', KasirController::class)->name('web.kasir');

    //route for transactions
    // Route::apiResource('transactions', TransactionController::class);
    Route::post('/transactions/batch', [TransactionController::class, 'storeBatch']);
    // Route::get('/transactions/report/daily', [TransactionController::class, 'dailyReport']);
    // Route::get('/transactions/statistics', [TransactionController::class, 'statistics']);
});

//route for change language
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('cms.language');


require __DIR__ . '/cms.php';

