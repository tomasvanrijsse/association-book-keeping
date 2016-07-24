<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class lid_ruimte extends PNCT_Model {

    public $id;
    public $lid_id;
    public $ruimte_id;
    public $startdatum;
    public $einddatum;

    /** CUSTOM lid_ruimte FUNCTIONS **/

    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/

    public function create(){
        $this->startdatum = date('Y-m-d');
        parent::create();
    }
    
    public function endActiveRuimte(){
        $sql =  'SELECT * FROM lid_ruimte WHERE lid_id = '.$this->lid_id.' AND einddatum IS NULL';
        $query = $this->db->query($sql);
        
        $this->fillObject($query->row());
        $this->einddatum = date('Y-m-d');
        $this->update();
    }
    
}