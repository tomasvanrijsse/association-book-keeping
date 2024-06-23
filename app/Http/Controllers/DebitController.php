<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DebitController extends Controller {

    public function index(Budget $budget = null)
    {
        if(!$budget){
            $transactions = Transaction::query()
                ->where('type', 'debet')
                ->doesntHave('booking')
                ->get();
        } else {
            $transactions = Transaction::query()
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
        $transaction = Transaction::query()->find($request->input('transaction_id'));

        Booking::updateOrCreate(
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
