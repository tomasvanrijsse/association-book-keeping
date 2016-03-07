<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class creditgroep_transactie extends PNCT_Model {

    public $id;
    public $creditgroep_id;
    public $transactie_id;

    /** CUSTOM creditgroup_transactie FUNCTIONS **/

    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/

    public function getTransacties($cg_id){
        $this->db->order_by('datum DESC');
        $this->db->where('id IN (SELECT transactie_id FROM creditgroep_transactie where creditgroep_id = '.$cg_id.')');
        return $this->transactie->readAll();
    }
    
}