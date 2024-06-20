<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Budget;
use App\Models\CreditGroup;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CreditController extends Controller {

    public function transacties(){
        $transactions = Transaction::query()
            ->where('type', 'credit')
            ->whereNull('creditgroep_id')
            ->doesntHave('booking')
            ->orderBy('datum','desc')
            ->get();

        return view('credit/transacties', [
            'transactions' => $transactions
        ]);
    }

    public function index(CreditGroup $creditGroup=null){

        if(is_null($creditGroup)){
            $transactions = Transaction::query()
                ->where('type', 'credit')
                ->whereNull('creditgroep_id')
                ->doesntHave('booking')
                ->orderBy('datum','desc')
                ->get();
        } else {
            $transactions = Transaction::query()
                ->where('creditgroep_id',$creditGroup->id)
                ->orderBy('van_naam')
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
        $creditGroup = new creditGroup();
        $creditGroup->naam = $request->input('naam');
        $creditGroup->jaar = date('Y');
        $creditGroup->save();

        return redirect('/credit');
    }

    public function transactieGroep(){
        //transaction koppelen
        $t = new transaction($this->input->post('tid'));
        $t->creditgroep_id = $this->input->post('gid');
        $t->update();

        $this->load->model('creditGroup');
        $groep = new creditGroup($this->input->post('gid'));
        echo prijsify($groep->saldo);
    }

    public function groepen_verdelen(){
        $data['creditgroups'] = CreditGroup::query()->orderBy('id','desc')->get();
        $data['budgets'] = Budget::orderBy('naam','asc')->get();

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

        echo json_encode($result);
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
        echo $budget->saldo;
    }
}
