<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $naam
 * @property $status
 * @property $jaar
 */
class CreditGroup extends Model {

    protected $table = 'creditgroep';

    private $sql = 'SELECT creditGroup.*, credit.bedrag as credit, debet.bedrag as debet, IFNULL(credit.bedrag,0) - IFNULL(debet.bedrag,0) as saldo
	FROM creditGroup
    LEFT JOIN (SELECT ROUND(SUM(bedrag),2) as bedrag,creditgroep_id FROM transaction GROUP BY creditgroep_id) as credit
    ON credit.creditgroep_id = creditGroup.id
    LEFT JOIN (SELECT ROUND(SUM(bedrag),2) as bedrag,creditgroep_id FROM boeking GROUP BY creditgroep_id) as debet
    ON debet.creditgroep_id = creditGroup.id
    %s
    ORDER BY id DESC';
    /** CUSTOM creditgroup FUNCTIONS **/

    function read($id = null){
        $query = $this->db->query(sprintf($this->sql,'where creditGroup.id = '.$id));
        $result = $query->result();
        return $this->fillObject($result[0]);
    }

    function query(){
        $query = $this->db->query(sprintf($this->sql,''));

        return $this->fillObjects($query);
    }

}
