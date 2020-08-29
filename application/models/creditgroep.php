<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed!');

class creditgroep extends PNCT_Model {

    public $id;
    public $naam;
    public $status;
    public $jaar;

    private $sql = 'SELECT creditgroep.*, credit.bedrag as credit, debet.bedrag as debet, IFNULL(credit.bedrag,0) - IFNULL(debet.bedrag,0) as saldo
	FROM creditgroep
    LEFT JOIN (SELECT ROUND(SUM(bedrag),2) as bedrag,creditgroep_id FROM transactie GROUP BY creditgroep_id) as credit
    ON credit.creditgroep_id = creditgroep.id
    LEFT JOIN (SELECT ROUND(SUM(bedrag),2) as bedrag,creditgroep_id FROM boeking GROUP BY creditgroep_id) as debet
    ON debet.creditgroep_id = creditgroep.id
    %s
    ORDER BY id DESC';
    /** CUSTOM creditgroup FUNCTIONS **/

    function read($id = null){
        $query = $this->db->query(sprintf($this->sql,'where creditgroep.id = '.$id));
        $result = $query->result();
        return $this->fillObject($result[0]);
    }

    function query(){
        $query = $this->db->query(sprintf($this->sql,''));

        return $this->fillObjects($query);
    }

}
