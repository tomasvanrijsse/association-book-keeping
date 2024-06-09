<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $budget_id
 * @property $bedrag
 * @property $datum
 * @property $transactie_id
 * @property $creditgroep_id
 */
class Booking extends Model {

    protected $table = 'boeking';

    public function vrijSaldo(){
        if(property_exists($this,'_vrijsaldo')){
            return $this->_vrijsaldo;
        }
        $sql1 = 'SELECT SUM(bedrag) as open FROM transaction WHERE type = \'credit\' AND status = 1';
        $query1 = $this->db->query($sql1);
        $row1 = $query1->row();

        $sql2 = 'SELECT SUM(bedrag) as used FROM boeking';
        $query2 = $this->db->query($sql2);
        $row2 = $query2->row();

        $this->_vrijsaldo = $row1->open - $row2->used;
        return $this->_vrijsaldo;
    }
}
