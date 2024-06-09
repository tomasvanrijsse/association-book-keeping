<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $naam
 */
class Budget extends Model {

    function saldo()
    {
        $sql = 'SELECT SUM(bedrag) as saldo FROM boeking WHERE budget_id = ' . $this->id;
        $query = $this->db->query($sql);
        $row = $query->row();

        $this->_saldo = $row->saldo;
        return round($row->saldo, 2);
    }

    public function create(){
        parent::create();

        $boeking = new boeking();
        $boeking->bedrag = 0;
        $boeking->budget_id = $this->id;
        $boeking->_created = date('Y-m-d');
    }
}
