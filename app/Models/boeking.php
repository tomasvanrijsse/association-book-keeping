<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class boeking extends PNCT_Model {

    public $id;
    public $budget_id;
    public $bedrag;
    public $datum;
    public $transactie_id;
    public $creditgroep_id;

    /** CUSTOM boeking FUNCTIONS **/

    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/
    
    public function vrijSaldo(){
        if(property_exists($this,'_vrijsaldo')){
            return $this->_vrijsaldo;
        }
        $sql1 = 'SELECT SUM(bedrag) as open FROM transactie WHERE type = \'credit\' AND status = 1 AND naar = (SELECT rekeningnr FROM account WHERE id = '.ACCOUNT_ID.')';
        $query1 = $this->db->query($sql1);
        $row1 = $query1->row(); 
        
        $sql2 = 'SELECT SUM(bedrag) as used FROM boeking WHERE budget_id IN (SELECT id FROM budget WHERE budget.account_id = '.ACCOUNT_ID.')';
        $query2 = $this->db->query($sql2);
        $row2 = $query2->row(); 
        
        $this->_vrijsaldo = $row1->open - $row2->used;
        return $this->_vrijsaldo;
    }
}