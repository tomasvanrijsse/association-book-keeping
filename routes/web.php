<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ContributionPeriodAllocateController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\CreditGroupAllocateController;
use App\Http\Controllers\CreditTransactions;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MandatesController;
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

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', LoginController::class)->middleware('guest');

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/', function () {
        return redirect('/home');
    });

    Route::get('/home', [HomeController::class,'index'])->name('home');
    Route::get('/export', \App\Http\Controllers\ExportsController::class)->name('export');
    Route::post('/import', ImportController::class);

    Route::get('/mandates', [MandatesController::class, 'index'])->name('mandates');
    Route::post('/mandates', [MandatesController::class, 'store']);

    Route::get('/budgets/{budget?}', [BudgetController::class,'index'])->name('budgets');
    Route::post('/new-budget', [BudgetController::class,'create']);
    Route::patch('/budgets/{budget}', [BudgetController::class,'update']);
    Route::delete('/budgets/{budget}', [BudgetController::class,'delete']);

    Route::get('/debit/{budget?}', [DebitController::class,'index'])->name('debit');
    Route::post('/debit/saveBudgetMutation', [DebitController::class,'saveBudgetMutation']);

    Route::get('/contribution-periods/allocate', [ContributionPeriodAllocateController::class, 'index']);
    Route::get('/contribution-periods/{contributionPeriod}/mutations', [ContributionPeriodAllocateController::class, 'budgetMutations']);
    Route::post('/contribution-periods/saveBudgetMutation', [ContributionPeriodAllocateController::class, 'saveBudgetMutation']);

    Route::get('/credit/transactions', [CreditTransactions::class,'index']);
    Route::post('/credit/transactions', [CreditTransactions::class,'saveBudgetMutation']);

    Route::get('/credit/{contributionPeriod?}', [CreditController::class,'index'])->name('credit');
    Route::post('/credit', [CreditController::class,'createContributionPeriod']);
    Route::post('/credit/assign-transaction', [CreditController::class,'assignTransactionToContributionPeriod']);
});
