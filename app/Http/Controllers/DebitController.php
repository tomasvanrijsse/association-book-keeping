<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class DebitController extends Controller {

    public function index(Budget $budget = null)
    {
        if(!$budget){
            $transactions = Transaction::query()
                ->where('type', 'debet')
                ->doesntHave('booking')
                ->orderBy('datum','desc')
                ->get();
        } else {
            $transactions = Transaction::query()
                ->where('type', 'debet')
                ->onBudget($budget)
                ->orderBy('datum', 'desc')
                ->get();
        }

        return view('debet/index', [
            'budgets' => Budget::query()->orderBy('naam')->get(),
            'activeBudget' => $budget,
            'transactions' => $transactions,
        ]);
    }

}
