<?php

namespace App\Http\Controllers;

use App\Models\BudgetMutation;
use App\Models\Budget;
use App\Models\ContributionPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContributionPeriodAllocateController extends Controller
{

    public function index(){
        $data = [
            'contributionPeriods' => ContributionPeriod::query()->orderBy('id','desc')->get(),
            'budgets' => Budget::all(),
        ];

        return view('credit.allocate-contribution-periods',$data);
    }

    public function budgetMutations(ContributionPeriod $contributionPeriod){
        $result = array('budgetten'=>array(),'boekingen'=>array());

        $budgets = Budget::all();
        foreach($budgets as $budget){
            $result['budgetten'][$budget->id] = round($budget->balance,2);
        }

        $boekingen = BudgetMutation::query()
            ->selectRaw('SUM(amount) as balance, budget_id')
            ->where('contribution_period_id', $contributionPeriod->id)
            ->groupBy('budget_id')
            ->get();

        foreach($boekingen as $boeking){
            $result['boekingen'][$boeking->budget_id] = (float)$boeking->balance;

            if(array_key_exists($boeking->budget_id,$result['budgetten'])){
                $result['budgetten'][$boeking->budget_id] -= round($boeking->balance,2);
            }
        }

        $result['saldo'] = $contributionPeriod->credit;

        return response()
            ->json($result);
    }

    public function saveBudgetMutation(Request $request){
        BudgetMutation::updateOrCreate(
            [
                'budget_id' =>  $request->input('budget_id'),
                'contribution_period_id' =>  $request->input('contribution_period_id')
            ],
            [
                'amount' => $request->input('amount')
            ]
        );

        return response()->noContent();
    }
}
