<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Http\Controllers\BudgetTargetsController;
use App\Http\Controllers\HomeController;

Route::get('/', ['uses' => HomeController::class . '@index']);
Route::get('/budget-targets', ['uses' => BudgetTargetsController::class . '@index']);