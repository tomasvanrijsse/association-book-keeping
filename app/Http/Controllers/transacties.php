<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class transacties extends CI_Controller {

	public function index()
	{
        $data = array();
        $this->load->view('transacties/index', $data);
	}
    
    public function counter($status='open'){
        $data = array();

        set_title('Tegengestelde transacties');
        
        $this->load->model('countersuggestie');
        $data['transactieSets'] = $this->countersuggestie->getCounterTransactions($status);
        $data['status'] = ($status=='open'?1:($status=='verborgen'?0:2));
        enqueue_stylesheet('/css/counter_transactions.css');
        enqueue_script('/js/counter_transactions.js');
        enqueue_script('/js/libs/jquery-ui-1.10.1.min.js');
        $this->load->view('transacties/counter', $data);
    }
    
    public function hideSuggestion(){
        $this->load->model('countersuggestie');
        $this->load->model('countersuggestie_transactie');
        $this->load->model('transactie');
        
        $this->countersuggestie->hide($this->input->post('id'));
        
        $cs = new countersuggestie($this->input->post('suggestie_id'));
        if(is_numeric($cs->transactie_id)){
            $t = new transactie($cs->transactie_id);
            $t->delete();
        }
        
        $this->countersuggestie_transactie->unchoose($this->input->post('suggestie_id'));
    }
    
    public function saveSuggestion(){
        $this->load->model('countersuggestie_transactie');
        $this->load->model('countersuggestie');
        $cs = new countersuggestie($this->input->post('suggestie_id'));
        
        // INDIEN ER EERDER GEKOZEN TRANSACTIES WAREN VERSCHILLEN CONTROLEREN
        if($cs->status==2){
        
            // EERST EERDER GEKOZEN TRANSACTIES OPHALEN
            $this->db->where('counter_id',$this->input->post('suggestie_id'));
            $this->db->where('status',1);
            $csts = $this->countersuggestie_transactie->readAll();

            $changed = false;
            foreach($csts as $cst){
                /* @var $cst countersuggestie_transactie */
                if($cst->transactie_id != $this->input->post('debet_id') &&
                   $cst->transactie_id != $this->input->post('credit_id')){
                    $changed = true;
                }
            }
            if(!$changed){
                exit('unchanged');
            } else {
                $this->countersuggestie_transactie->unchoose($this->input->post('suggestie_id'));
            }
            
        }
        
        
        $this->load->model('transactie');
        if(is_numeric($cs->transactie_id)){
            $t = new transactie($cs->transactie_id);
            $t->delete();
        }
        
        $this->countersuggestie_transactie->choose($this->input->post('suggestie_id'),$this->input->post('debet_id'));
        $this->countersuggestie_transactie->choose($this->input->post('suggestie_id'),$this->input->post('credit_id'));
        $debet = new transactie($this->input->post('debet_id'));
        $credit = new transactie($this->input->post('credit_id'));
        
        $newid = NULL;
        if($debet->bedrag != $credit->bedrag){
            $new = new transactie();
            $newbedrag = $credit->bedrag - $debet->bedrag;
            if($newbedrag < 0){
                //debet
                $new->fillObject($debet);
                $new->bedrag = $newbedrag * -1;
            } else {
                //credit
                $new->fillObject($credit);
                $new->bedrag = $newbedrag;
            }
            $new->description = 'MERGED * '.$new->description;
            $new->status = 2;
            unset($new->id); //overgenomen bij fillObject
            $new->create();   
            $newid = $new->id;
        }
        
        $this->countersuggestie->used($this->input->post('suggestie_id'),$newid);
        
        $debet->deactivate();
        $credit->deactivate();
    }
    
}