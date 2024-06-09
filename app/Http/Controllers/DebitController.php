<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class DebitController extends Controller {

    public function index(Budget $budget = null)
    {
        $data = [
            'budgets' => Budget::query()->orderBy('naam')->get()
        ];

        if(!$budget){
            $data['transacties'] = Transaction::query()->openDebit()->get();
            $data['transacties_title'] = 'Ongecategoriseerde transacties';
            $data['active_budget'] = false;
        } else {
            $transacties = Transaction::query()
                ->whereHas('booking', function (Builder $query) use ($budget) {
                    $query->where('budget_id', $budget->id)
                        ->where('bedrag', '<' , 0);
                })
                ->orderBy('datum', 'desc')->get();

            $data['transacties'] = $transacties;
            $data['transacties_title'] = $budget->naam.' transacties';
            $data['active_budget'] = $budget->id;
        }

        return view('debet/index', $data);
    }

}
