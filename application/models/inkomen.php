<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class inkomen extends PNCT_Model {

    public $id;
    public $lid_id;
    public $startdatum;
    public $einddatum;
    public $bedrag;
    public $naam;

    /** CUSTOM inkomen FUNCTIONS **/

    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/
    
    public function create(){
        $this->startdatum = date('Y-m-d');
        parent::create();
    }
}