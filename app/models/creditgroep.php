<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class creditgroep extends PNCT_Model {

    public $id;
    public $naam;
    public $status;
    public $account_id;
    public $jaar;

    /** CUSTOM creditgroup FUNCTIONS **/

    /*function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }*/

    function read($id){
        $where = 'WHERE c.id = '.$id;
        $query = $this->query($where);
        if(is_object($query->row(0))){
            return $this->fillObject($query->row(0));
        }
        else { return FALSE; }
    }
    
    function readAllByVars(){
        $where = '';
        foreach($this as $attr => $value){
            if(isset($value) && property_exists($this, $attr)){
                if($where != ''){
                    $where .= ' AND ';
                } else {
                    $where = 'WHERE ';
                }
                $where .= 'c.'.$attr .'= "'.$value.'"';
            }
        }
        $query = $this->query($where);
        return $this->fillObjects($query);
    }
    
    function query($where=''){
        $sql = 'SELECT c2.*, credit.bedrag as credit, debet.bedrag as debet, IFNULL(credit.bedrag,0) - IFNULL(debet.bedrag,0) as saldo
FROM (
	SELECT c.*, IFNULL(MIN(t.datum),CURDATE()) as datum
	FROM creditgroep c
	LEFT JOIN transactie t ON c.id = t.creditgroep_id
    '.$where.'
	GROUP BY c.id
) as c2
LEFT JOIN (SELECT ROUND(SUM(bedrag),2) as bedrag,creditgroep_id FROM transactie GROUP BY creditgroep_id) as credit
ON credit.creditgroep_id = c2.id
LEFT JOIN (SELECT ROUND(SUM(bedrag),2) as bedrag,creditgroep_id FROM boeking GROUP BY creditgroep_id) as debet
ON debet.creditgroep_id = c2.id
ORDER BY datum DESC';
        return $this->db->query($sql);
    }
    
}