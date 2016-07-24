<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class ruimte extends PNCT_Model {

    public $id;
    public $naam;
    public $verdieping;
    public $percentage;

    /** CUSTOM ruimte FUNCTIONS **/

    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/

    public function name(){
        return $this->verdieping.'e - '.$this->naam;
    }
    
}