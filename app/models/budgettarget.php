<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class budgettarget extends PNCT_Model {

    public $id;
    public $bedrag;
    public $budget_id;
    public $datum;
    public $type;
    public $opmerking;

    /** CUSTOM post FUNCTIONS **/
    
    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/

    function current_target($saldo,$lastcreditgrouptime){
        if($this->id == "") return false;
        
        switch($this->type){
            case 'vast':
                $target = $this->bedrag;
                break;
            case 'target':
                $date1 = new DateTime(date('Y-m-d',$lastcreditgrouptime));
                $date2 = new DateTime($this->datum);
                $interval = $date1->diff($date2);
                $months = $interval->y*12 + $interval->m;
                if($months<=1){
                    return $this->bedrag;
                }
                $target = $saldo + ($this->bedrag - $saldo) / $months;
                break;
            case 'increment':
                $target = $saldo + $this->bedrag;
                break;
        }
        
        return round($target,2);
        
    }
    
}