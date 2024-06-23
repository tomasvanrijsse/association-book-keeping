<?php

namespace App\Http\Controllers;

use App\Models\BudgetMutation;
use App\Models\Budget;
use App\Models\BankTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DebitController extends Controller {

    public function index(Budget $budget = null)
    {
        if(!$budget){
            $transactions = BankTransaction::query()
                ->where('type', 'debet')
                ->doesntHave('booking')
                ->get();
        } else {
            $transactions = BankTransaction::query()
                ->where('type', 'debet')
                ->onBudget($budget)
                ->get();
        }

        return view('debet/index', [
            'budgets' => Budget::query()->get(),
            'activeBudget' => $budget,
            'transactions' => $transactions,
        ]);
    }
    public function saveBooking(Request $request){
        $transaction = BankTransaction::query()->find($request->input('transaction_id'));

        BudgetMutation::updateOrCreate(
            [
                'transactie_id' =>  $transaction->id,
            ],
            [
                'budget_id' =>  $request->input('budget_id'),
                'bedrag' => $transaction->bedrag * -1,
            ]
        );

        $budget = Budget::find($request->input('budget_id'));
        echo prijsify($budget->saldo);
    }
}
