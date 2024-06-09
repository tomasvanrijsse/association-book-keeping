<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BudgetController extends Controller {

    public function index(){
        return view('budgets/index', [
            'budgetten' => Budget::query()->orderBy('naam')->get()
        ]);
    }

    public function create(Request $request){
        $budget = new budget();
        $budget->naam = $request->input('naam');
        $budget->save();

        return redirect($request->input('redirectUrl'));
    }

    public function delete(Budget $budget)
    {
        $budget->delete();
        return response()->noContent();
    }

    public function transactieBudget(){
        $transactie = new transaction($this->input->post('tid'));
        //boeking aanmaken
        $boeking = new boeking();
        $boeking->transactie_id = $transactie->id;

        $bedrag = $transactie->bedrag;
        if($transactie->type == 'debet'){
            $bedrag *= -1;
        }

        if($boeking->readByVars()){
            $boeking->budget_id = $this->input->post('bid');
            $boeking->bedrag = $bedrag;
            $boeking->update();
        } else {
            $boeking->budget_id = $this->input->post('bid');
            $boeking->bedrag = $bedrag;
            $boeking->datum = $transactie->datum;
            $boeking->create();
        }

        $budget = new budget();
        $budget->read($this->input->post('bid'));
        echo prijsify($budget->saldo);
    }
}
