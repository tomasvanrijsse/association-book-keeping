<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class budget extends PNCT_Model {

    public $id;
    public $naam;

    /** CUSTOM post FUNCTIONS **/
    
    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/

    function __get($key){
        switch($key){
            case 'saldo':        
                if(property_exists($this,'_saldo')){
                    return $this->_saldo;
                }
                $sql = 'SELECT SUM(bedrag) as saldo FROM boeking WHERE budget_id = '.$this->id;
                $query = $this->db->query($sql);
                $row = $query->row(); 
                                
                $this->_saldo = $row->saldo;
                return round($row->saldo,2);
            break;
            default:
                return parent::__get($key);         
            break;
        }
    }
 
    
    public function create(){
        parent::create();
        
        $boeking = new boeking();
        $boeking->bedrag = 0;
        $boeking->budget_id = $this->id;
        $boeking->_created = date('Y-m-d');
    }
}
