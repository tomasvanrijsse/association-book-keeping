<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class transactie extends PNCT_Model {

    public $id;
    public $bedrag;
    public $van;
    public $van_naam;
    public $naar;
    public $datum;
    public $description;
    public $transactietype;
    public $type;
    public $bankrekening_id;
    public $creditgroep_id;
    public $status;

    /** CUSTOM transactie FUNCTIONS **/

    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/
       
    private function setAccount(){
        $account = new account();
        $account->read(ACCOUNT_ID);
        $this->db->where('naar',$account->rekeningnr);
    }
    
    public function getFromLid($id,$year,$type){
        $this->setAccount();
        $this->db->order_by('datum DESC');
        $this->db->where('YEAR(datum)',$year);
        $this->db->where('bankrekening_id IN (SELECT id FROM bankrekening WHERE lid_id = '.$id.')');
        $this->db->where('status >=',1);
        $this->db->where('type',$type);
        return $this->readAll();
    }
    
    public function getOpenDebet(){
        $this->setAccount();
        $this->db->order_by('datum DESC');
        $this->db->where('type','debet');
        $this->db->where('id NOT IN (SELECT transactie_id FROM boeking WHERE transactie_id IS NOT NULL AND bedrag < 0)');
        $this->db->where('status >=',1);
        return $this->readAll();
    }
    
    public function getOpenCredit(){
        $this->setAccount();
        $this->db->select('transactie.*');
        $this->db->order_by('transactie.datum DESC');
        $this->db->where('type','credit');
        $this->db->join('boeking','boeking.transactie_id = transactie.id','left');
        $this->db->where('boeking.id IS NULL');
        $this->db->where('transactie.creditgroep_id IS NULL');
        $this->db->where('status >=',1);
        return $this->readAll();
    }
           
    public function deactivate(){
        //transactie uit + debit uitschakelen
        $this->db->where('id',$this->id);
        $this->db->update($this->getTableName(), array('status' => '0'));
        
        //credit boeking verwijderen
        $this->db->where('transactie_id',$this->id);
        $this->db->delete('boeking');
    }
    
    public function getGroepTransacties($groep_id){
        $this->db->order_by('van_naam');
        $this->db->where('creditgroep_id',$groep_id);
        return $this->transactie->readAll();
    }
    
    /* aangepaste exists/countByVars voor de import, zodat niet alle Vars meegenomen worden. */
    function cleanExists(){
        $this->db->from($this->getTableName());
        foreach($this as $attr => $value){
            if(isset($value) && property_exists($this, $attr) && !in_array($attr,array('bankrekening_id','status'))){
                $this->db->where($this->getTableName().'.'.$attr,$value);
            }
        }
        if($this->db->count_all_results()==1){
            return true;
        } else {
            return false;
        }
    }
         
}
