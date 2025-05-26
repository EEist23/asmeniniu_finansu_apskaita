<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Čia registruojami visi žiniatinklio (web) maršrutai. Šie maršrutai
| yra įkeliami per RouteServiceProvider ir visi turi "web" middleware.
|
*/

// Pagrindinis puslapis (neprisijungus)
Route::get('/', function () {
    return view('welcome');
});

// Autentifikuotiems vartotojams
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Po prisijungimo nukreipiama čia
    Route::get('/dashboard', function () {
        return redirect()->route('categories.index'); // arba 'transactions.index'
    })->name('dashboard');

    // Kategorijų CRUD
    Route::resource('categories', CategoryController::class);

    // Pajamų/išlaidų įrašų CRUD
    Route::resource('transactions', TransactionController::class);
});
