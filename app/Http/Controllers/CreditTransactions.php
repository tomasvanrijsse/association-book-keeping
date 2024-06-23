<?php

namespace App\Http\Controllers;

use App\Models\BudgetMutation;
use App\Models\Budget;
use App\Models\BankTransaction;
use Illuminate\Http\Request;

class CreditTransactions extends Controller
{

    public function index(){
        $transactions = BankTransaction::query()
            ->where('type', 'credit')
            ->whereNull('creditgroep_id')
            ->doesntHave('booking')
            ->get();

        return view('credit/transactions', [
            'transactions' => $transactions,
            'budgets' => Budget::all()
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
                'bedrag' => $transaction->bedrag,
            ]
        );

        $budget = Budget::find($request->input('budget_id'));
        echo prijsify($budget->saldo);
    }
}
