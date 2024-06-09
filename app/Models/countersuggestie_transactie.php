<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class countersuggestie_transactie extends PNCT_Model {

    public $id;
    public $counter_id;
    public $transactie_id;
    public $status;

    public function getTransactions($counter_id){
        $this->db->select('cst.`status` as sug_status, transaction.*', FALSE);
        $this->db->join('countersuggestie_transactie cst','cst.transactie_id = transaction.id');
        $this->db->where('counter_id',$counter_id);
        return $this->transactie->readAll();
    }

    public function choose($counter_id, $transaction_id){
        $this->db->where('counter_id',$counter_id);
        $this->db->where('transactie_id',$transaction_id);
        $this->db->update($this->getTableName(),array('status'=>'1'));
    }

    public function unchoose($counter_id){
        $this->db->where('counter_id',$counter_id);
        $this->db->update($this->getTableName(),array('status'=>'0'));
    }
}
