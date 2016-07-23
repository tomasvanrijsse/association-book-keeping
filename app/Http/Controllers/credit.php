<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'budgetten.php';

class credit extends budgetten {
   
    public function index()
    {
        if(getSetting(SETTING_USECREDITGROUPS)){
            redirect('/credit/groepen');
        } else {
            redirect('/credit/bedragen');
        }
    }
    
    public function transacties(){
        $data = $this->_initData();
        
        $data['transacties'] = $this->transactie->getOpenCredit();
        
        set_title('Credit | Transacties verdelen');
        enqueue_script('/js/libs/jquery-ui-1.10.1.min.js');
        enqueue_stylesheet('/css/smoothness/jquery-ui-1.10.1.custom.min.css');
        enqueue_script('/js/libs/jquery.pajinate.js');
        enqueue_script('/js/credit_transacties.js');
        enqueue_stylesheet('/css/credit_transacties.css');
        
        $this->load->view('credit/transacties', $data);
    }
    
    // NIET MEER IN GEBRUIK
    /*public function bedragen(){
        if(getSetting(SETTING_USECREDITGROUPS)) redirect('/credit');
        set_title('Credit | Bedragen verdelen');
        enqueue_script('/js/credit_bedragen.js');
        enqueue_stylesheet('/css/credit_bedragen.css');
        
        $data = $this->_initData();
        $data['vrijSaldo'] = $this->boeking->vrijSaldo();
        $this->load->view('credit/bedragen', $data);
    }
    
    // NIET MEER IN GEBRUIK
    public function saveBoeking(){
        if(getSetting(SETTING_USECREDITGROUPS)) redirect('/credit');
        $boeking = new boeking();
        $boeking->bedrag = $this->input->post('amount');
        $boeking->budget_id = $this->input->post('budget_id');
        $boeking->create();
        
        $budget = new budget($this->input->post('budget_id'));
        echo $budget->saldo;
    }*/
    
    public function groepen($groep=null){
        $data=array();
        $this->load->model('creditgroep');
        $this->creditgroep->account_id = ACCOUNT_ID;
        $this->db->order_by('id DESC');
        $data['creditgroups'] = $this->creditgroep->readAllByVars();
        
        if(is_null($groep)){
            $data['transacties'] = $this->transactie->getOpenCredit();
            $data['groep'] = false;
            $data['active_groep'] = false;
        } else {
            $data['transacties'] = $this->transactie->getGroepTransacties($groep->id);
            $data['active_groep'] = $groep->id;
            $data['groep'] = $groep;
        }
        
        set_title('Credit | Transacties groeperen');
        enqueue_script('/js/libs/jquery-ui-1.10.1.min.js');
        enqueue_stylesheet('/css/smoothness/jquery-ui-1.10.1.custom.min.css');
        enqueue_script('/js/libs/jquery.pajinate.js');
        enqueue_stylesheet('/css/credit_groepen.css');
        enqueue_script('/js/credit_groepen.js');
        
        $this->load->view('credit/groepen',$data);
    }
    
    public function groep_detail($creditgroep_id){
        $this->load->model('creditgroep');
        $groep = new creditgroep();
        $groep->read($creditgroep_id);
        //$groep->account_id = ACCOUNT_ID;
        //$groep->naam = html_entity_decode(rawurldecode($budget_name));
        if($groep){
            set_title('Credit | Groep | '.ucfirst($groep->naam));
            $this->groepen($groep);
        } else {
            $this->session->set_flashdata('error', 'Het budget "'.$groep->naam.'" bestaat niet');
            redirect('/credit/groepen');
        }
    }
    
    public function addGroep(){
        $this->load->model('creditgroep');
        $groep = new creditgroep();
        $groep->naam = $this->input->post('naam');
        $groep->status = 1;
        $groep->account_id = ACCOUNT_ID;
        $groep->jaar = date('Y');
        if($response = $groep->create()){
            redirect('/credit/groepen');
        } else {
            var_dump($response);
        }
    }
    
    public function transactieGroep(){
        //transactie koppelen
        $t = new transactie($this->input->post('tid'));
        $t->creditgroep_id = $this->input->post('gid');
        $t->update();
        
        $this->load->model('creditgroep');
        $groep = new creditgroep($this->input->post('gid'));
        echo prijsify($groep->saldo);
    }
    
    public function groepen_verdelen(){
        $data = $this->_initData();
        $this->load->model('creditgroep');
        $this->creditgroep->account_id = ACCOUNT_ID;
        $this->db->order_by('id DESC');
        $data['creditgroups'] = $this->creditgroep->readAllByVars();
        $data['transacties'] = $this->transactie->getOpenCredit();
        
        $lastcgtime = 0;
        foreach($data['creditgroups'] as $cg){
            $time = strtotime($cg->datum);
            if($time>$lastcgtime && $cg->saldo != $cg->credit){
                $lastcgtime = $time;                
            }
        }
                
        $this->load->model('budgettarget');
        foreach($data['budgetten'] as &$budget){
            $budgettarget = new budgettarget();
            $budgettarget->budget_id = $budget->id;
            $budgettarget->active = 1;
            $budgettarget->readByVars();
            
            $budget->target = $budgettarget->current_target($budget->saldo,$lastcgtime);
        }
        
        set_title('Credit | Groepen verdelen');
        
        enqueue_script('/js/credit_groepen_verdelen.js');
        enqueue_stylesheet('/css/credit_bedragen.css');
        
        $this->load->view('credit/groepen_verdelen',$data);
    }
    
    public function groep_info($id){
        $result = array('budgetten'=>array(),'boekingen'=>array());
        
        $budget = new budget();
        $budget->account_id = ACCOUNT_ID;
        $budgets = $budget->readAllByVars();
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
        
        $this->load->model('creditgroep');
        $group = new creditgroep($id);
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
            $this->load->model('creditgroep');
            $creditgroep = new creditgroep($this->input->post('creditgroep_id'));
        
            $boeking->bedrag = $this->input->post('amount');
            $boeking->datum = $creditgroep->datum;
            $boeking->create();
        }
        
        $budget = new budget($this->input->post('budget_id'));
        echo $budget->saldo;
    }
}