<?php

namespace App\Http\Controllers;

use App\Models\BudgetMutation;
use App\Models\Budget;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class CreditTransactions extends Controller
{

    public function index(){
        $transactions = BankTransaction::query()
            ->where('type', 'credit')
            ->whereNull('contribution_period_id')
            ->doesntHave('budgetMutation')
            ->get();

        return view('credit/transactions', [
            'transactions' => $transactions,
            'budgets' => Budget::all()
        ]);
    }

    public function saveBudgetMutation(Request $request){
        $transaction = BankTransaction::query()->find($request->input('transaction_id'));

        BudgetMutation::updateOrCreate(
            [
                'bank_transaction_id' =>  $transaction->id,
            ],
            [
                'budget_id' =>  $request->input('budget_id'),
                'amount' => $transaction->amount,
            ]
        );

        $budget = Budget::find($request->input('budget_id'));
        echo Number::currency($budget->balance, 'EUR');
    }
}
