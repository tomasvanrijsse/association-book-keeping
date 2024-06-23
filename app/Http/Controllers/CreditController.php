<?php

namespace App\Http\Controllers;

use App\Models\ContributionPeriod;
use App\Models\BankTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CreditController extends Controller {

    public function index(ContributionPeriod $creditGroup=null){

        if(is_null($creditGroup)){
            $transactions = BankTransaction::query()
                ->where('type', 'credit')
                ->whereNull('creditgroep_id')
                ->doesntHave('booking')
                ->get();
        } else {
            $transactions = BankTransaction::query()
                ->where('creditgroep_id',$creditGroup->id)
                ->get();
        }

        return view('credit/groepen',[
            'transactions' => $transactions,
            'activeGroup' => $creditGroup,
            'creditGroups' => ContributionPeriod::query()
                ->with('transactions','bookings')
                ->orderBy('id','desc')
                ->get(),
        ]);
    }

    public function createCreditGroup(Request $request): RedirectResponse
    {
        $creditGroup = new ContributionPeriod();
        $creditGroup->naam = $request->input('naam');
        $creditGroup->jaar = date('Y');
        $creditGroup->save();

        return redirect('/credit');
    }


    public function assignTransactionToCreditGroup(Request $request){
        $group = ContributionPeriod::find($request->input('creditGroup_id'));

        $transaction = BankTransaction::query()->find($request->input('transaction_id'));
        $transaction->creditgroep_id = $group->id;
        $transaction->save();

        echo prijsify($group->saldo);
    }

}
