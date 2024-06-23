<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Budget;
use App\Models\CreditGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreditGroupAllocateController extends Controller
{

    public function index(){
        $data = [
            'creditgroups' => CreditGroup::query()->orderBy('id','desc')->get(),
            'budgets' => Budget::all(),
        ];

        return view('credit/groepen_verdelen',$data);
    }

    public function bookings(CreditGroup $creditGroup){
        $result = array('budgetten'=>array(),'boekingen'=>array());

        $budgets = Budget::all();
        foreach($budgets as $budget){
            $result['budgetten'][$budget->id] = round($budget->saldo,2);
        }

        $boekingen = Booking::query()
            ->selectRaw('SUM(bedrag) as totaal, budget_id')
            ->where('creditgroep_id', $creditGroup->id)
            ->groupBy('budget_id')
            ->get();

        foreach($boekingen as $boeking){
            $result['boekingen'][$boeking->budget_id] = (float)$boeking->totaal;

            if(array_key_exists($boeking->budget_id,$result['budgetten'])){
                $result['budgetten'][$boeking->budget_id] -= round($boeking->totaal,2);
            }
        }

        $result['saldo'] = $creditGroup->credit;

        return response()
            ->json($result);
    }

    public function saveBooking(Request $request){
        Booking::updateOrCreate(
            [
                'budget_id' =>  $request->input('budget_id'),
                'creditgroep_id' =>  $request->input('creditgroep_id')
            ],
            [
                'bedrag' => $request->input('amount')
            ]
        );

        $budget = Budget::find($request->input('budget_id'));
        echo prijsify($budget->saldo);
    }
}
