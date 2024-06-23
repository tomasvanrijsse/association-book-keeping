<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CreditTransactions extends Controller
{

    public function index(){
        $transactions = Transaction::query()
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
        $transaction = Transaction::query()->find($request->input('transaction_id'));

        Booking::updateOrCreate(
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
