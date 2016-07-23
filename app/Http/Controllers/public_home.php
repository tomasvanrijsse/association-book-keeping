<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class public_home extends CI_Controller {
       
    public function index(){
        $this->db->select('MAX(datum) as datum');
        $query = $this->db->get('transactie');
        if ($query->num_rows() > 0)
        {
            $row = $query->row(); 
            $last_transaction = date('d-m-Y',strtotime($row->datum));
        } else {
            $last_transaction = false;
        }
            
        $budget = new budget();
        $this->db->order_by('account_id,naam');
        $budgetten = $budget->readAllByVars();
        
        $this->load->model('budgettarget');
        $budgettarget = new budgettarget();
        $budgettarget->active = 1;
        $targets = $budgettarget->readAllByVars();
        
        foreach($budgetten as $budget){
            $budget->target = false;
            foreach($targets as $target){
                if($budget->id == $target->budget_id){
                    $target->datum = date('d-m-Y',strtotime($target->datum));
                    $budget->target = $target;
                }
            }
        }
        
        $data = array(
            'last_import' => getSetting(LAST_IMPORT),
            'last_transaction' => $last_transaction,
            'budgetten' => $budgetten
        );
        
        set_title('Home');
        enqueue_stylesheet('/css/public_home.css');
        enqueue_script('/js/public_home.js');
        
        $this->load->view('public_home/index',$data);
    }
    
    public function login(){
        $this->session->set_userdata('isAdmin','true');
        redirect('/');
    }
}