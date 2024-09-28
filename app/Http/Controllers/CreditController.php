<?php

namespace App\Http\Controllers;

use App\Models\ContributionPeriod;
use App\Models\BankTransaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class CreditController extends Controller {

    public function index(ContributionPeriod $contributionPeriod=null){

        if(is_null($contributionPeriod)){
            $transactions = BankTransaction::query()
                ->where('type', 'credit')
                ->whereNull('contribution_period_id')
                ->doesntHave('budgetMutation')
                ->get();
        } else {
            $transactions = BankTransaction::query()
                ->where('contribution_period_id',$contributionPeriod->id)
                ->get();
        }

        $lastContributionPeriod = ContributionPeriod::query()->orderByDesc('id')->first();
        $newContributionPeriodDate = Carbon::create(year: $lastContributionPeriod->year, month: $lastContributionPeriod->month + 1);

        return view('credit.assign-contribution-periods',[
            'transactions' => $transactions,
            'activePeriod' => $contributionPeriod,
            'contributionPeriods' => ContributionPeriod::query()
                ->with('transactions','budgetMutations')
                ->orderBy('id','desc')
                ->get(),
            'newContributionPeriodDate' => $newContributionPeriodDate,
        ]);
    }

    public function createContributionPeriod(Request $request): RedirectResponse
    {
        $contributionPeriod = new ContributionPeriod();
        $contributionPeriod->title = $request->input('title');
        $contributionPeriod->month = $request->input('month');
        $contributionPeriod->year = $request->input('year');
        $contributionPeriod->save();

        return redirect('/credit');
    }


    public function assignTransactionToContributionPeriod(Request $request){
        $period = ContributionPeriod::find($request->input('contribution_period_id'));

        $transaction = BankTransaction::query()->find($request->input('transaction_id'));
        $transaction->contribution_period_id = $period->id;
        $transaction->save();

        echo Number::currency($period->balance, 'EUR');
    }

}
