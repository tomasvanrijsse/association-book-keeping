<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', [HomeController::class,'index'])->name('home');
Route::get('/budgets', [BudgetController::class,'index'])->name('budgets');
Route::post('/budgets', [BudgetController::class,'create']);
Route::delete('/budgets/{budget}', [BudgetController::class,'delete']);

Route::get('/debit/{budget?}', [DebitController::class,'index'])->name('debet');

Route::get('/credit/transacties', [CreditController::class,'transacties']);
Route::get('/credit/groepen_verdelen', [CreditController::class,'groepen_verdelen']);
Route::get('/credit/{creditGroup?}', [CreditController::class,'index'])->name('credit');
