<?php

class PNCT_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('account_id') || !is_numeric($this->session->userdata('account_id'))){
            $this->session->set_userdata('account_id','1');
        }
        define('ACCOUNT_ID',$this->session->userdata('account_id'));
    }
    
}