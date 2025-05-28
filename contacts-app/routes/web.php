<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Nukreipimas į transakcijas po prisijungimo
    Route::get('/dashboard', function () {
        return redirect()->route('transactions.index'); 
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class);
});
