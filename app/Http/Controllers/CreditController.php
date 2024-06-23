<?php

namespace App\Http\Controllers;

use App\Models\CreditGroup;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CreditController extends Controller {

    public function index(CreditGroup $creditGroup=null){

        if(is_null($creditGroup)){
            $transactions = Transaction::query()
                ->where('type', 'credit')
                ->whereNull('creditgroep_id')
                ->doesntHave('booking')
                ->get();
        } else {
            $transactions = Transaction::query()
                ->where('creditgroep_id',$creditGroup->id)
                ->get();
        }

        return view('credit/groepen',[
            'transactions' => $transactions,
            'activeGroup' => $creditGroup,
            'creditGroups' => CreditGroup::query()
                ->with('transactions','bookings')
                ->orderBy('id','desc')
                ->get(),
        ]);
    }

    public function createCreditGroup(Request $request): RedirectResponse
    {
        $creditGroup = new CreditGroup();
        $creditGroup->naam = $request->input('naam');
        $creditGroup->jaar = date('Y');
        $creditGroup->save();

        return redirect('/credit');
    }


    public function assignTransactionToCreditGroup(Request $request){
        $group = CreditGroup::find($request->input('creditGroup_id'));

        $transaction = Transaction::query()->find($request->input('transaction_id'));
        $transaction->creditgroep_id = $group->id;
        $transaction->save();

        echo prijsify($group->saldo);
    }

}
