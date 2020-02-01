<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'budgetten.php';

class debet extends budgetten {

    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(){
        redirect('/debet/overzicht');
    }
    
    public function overzicht($budget=false)
    {        
        if(!$budget) set_title('Debet');
        
        $data = $this->_initData();
        
        if($budget==false){
            $data['transacties'] = $this->transactie->getOpenDebet();
            $data['transacties_title'] = 'Ongecategoriseerde transacties';
            $data['active_budget'] = false;
        } else {
            $transactie = new transactie();
            $this->db->select('transactie.*');
            $this->db->join('boeking','boeking.transactie_id = transactie.id AND boeking.bedrag < 0');
            $this->db->where('boeking.budget_id',$budget->id);
            $this->db->where('transactie.status',1);
            $this->db->order_by('transactie.datum DESC');
            $data['transacties'] = $transactie->readAll();
            $data['transacties_title'] = $budget->naam.' transacties';
            $data['active_budget'] = $budget->id;
        }
        enqueue_script('/js/libs/jquery-ui-1.10.1.min.js');
        enqueue_stylesheet('/css/smoothness/jquery-ui-1.10.1.custom.min.css');
        enqueue_script('/js/libs/jquery.pajinate.js');
        enqueue_script('/js/debet.js');
        enqueue_stylesheet('/css/debet.css');
        
        $this->load->view('debet/index', $data);
    }
        
    public function detail($budget_name){
        $budget = new budget();
        $budget->naam = html_entity_decode(rawurldecode($budget_name));
        if($budget->readByVars()){
            set_title('Debet | '.ucfirst($budget->naam));
            $this->overzicht($budget);
        } else {
            $this->session->set_flashdata('error', 'Het budget "'.$budget->naam.'" bestaat niet');
            redirect('/debet/overzicht');
        }
    }
}
