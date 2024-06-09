<?php

namespace App\Http\Controllers;

use App\Models\CreditGroup;
use App\Models\Transaction;

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

    public function groep_detail($creditgroep_id){
        $this->load->model('creditGroup');
        $groep = new creditGroup();
        $groep = $groep->read($creditgroep_id);
        //$groep->naam = html_entity_decode(rawurldecode($budget_name));
        if($groep){
            set_title('Credit | Groep | '.ucfirst($groep->naam));
            $this->groepen($groep);
        } else {
            $this->session->set_flashdata('error', 'Het budget "'.$groep->naam.'" bestaat niet');
            return redirect('/credit/groepen');
        }
    }

    public function addGroep(){
        $this->load->model('creditGroup');
        $groep = new creditGroup();
        $groep->naam = $this->input->post('naam');
        $groep->status = 1;
        $groep->jaar = date('Y');
        if($response = $groep->create()){
            return redirect('/credit/groepen');
        } else {
            var_dump($response);
        }
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
        $data = $this->_initData();
        $this->load->model('creditGroup');
        $data['creditgroups'] = $this->creditgroep->query();
        $data['transacties'] = $this->transactie->getOpenCredit();

        return view('credit/groepen_verdelen',$data);
    }

    public function groep_info($id){
        $result = array('budgetten'=>array(),'boekingen'=>array());

        $budget = new budget();
        $budgets = $budget->readAll();
        foreach($budgets as $key => $budget){
            $result['budgetten'][$budget->id] = round($budget->saldo,2);
        }

        $this->load->model('boeking');
        $this->db->select('SUM(`bedrag`) as totaal',FALSE);
        $this->db->select('budget_id');
        $this->db->where('creditgroep_id',$id);
        $this->db->group_by('budget_id');
        $boekingen = $this->boeking->readAll();
        foreach($boekingen as $boeking){
            $result['boekingen'][$boeking->budget_id] = (float)$boeking->totaal;

            if(array_key_exists($boeking->budget_id,$result['budgetten'])){
                $result['budgetten'][$boeking->budget_id] -= round($boeking->totaal,2);
            }
        }

        $this->load->model('creditGroup');
        $group = new creditGroup($id);
        $result['saldo'] = $group->credit;

        echo json_encode($result);
    }

    public function saveGroepBoeking(){
        $boeking = new boeking();
        $boeking->budget_id = $this->input->post('budget_id');
        $boeking->creditgroep_id = $this->input->post('creditgroep_id');
        if($boeking->readByVars()){
            $boeking->bedrag = $this->input->post('amount');
            $boeking->update();
        } else {
            $this->load->model('creditGroup');
            $creditgroep = new creditGroup($this->input->post('creditgroep_id'));

            $boeking->bedrag = $this->input->post('amount');
            $boeking->datum = date('Y-m-d');
            $boeking->create();
        }

        $budget = new budget($this->input->post('budget_id'));
        echo $budget->saldo;
    }
}
