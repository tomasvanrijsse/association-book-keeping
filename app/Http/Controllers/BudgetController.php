<?php

namespace App\Http\Controllers;

use App\Models\Budget;

class BudgetController extends Controller {

    public function index(){
        return view('budgets/index', [
            'budgetten' => Budget::query()->orderBy('naam')->get()
        ]);
    }

    public function addBudget(){
        $budget = new budget();
        $budget->naam = $this->input->post('naam');
        $budget->verborgen = 0;
        if($budget->create()){
            $this->index();
        } else {
            $this->index();
        }
    }

    protected function _initData(){
        $data = array();
        $budget = new budget();
        $budget->verborgen = 0;
        $this->db->order_by('naam');
        $data['budgetten'] = $budget->readAllByVars();
        return $data;
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
