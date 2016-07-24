<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Money\Money;

class Budget extends Model
{

    public $table = 'budget';

    public function getSaldo()
    {
        if (property_exists($this, '_saldo')) {
            return $this->_saldo;
        }
        $sql   = 'SELECT SUM(bedrag) as saldo FROM boeking WHERE budget_id = ' . $this->id;
        $query = $this->db->query($sql);
        $row   = $query->row();

        $this->_saldo = $row->saldo;

        return Money::EUR($row->saldo * 100);
    }

    public function budgetTarget()
    {
        return $this->hasOne('App\Models\BudgetTarget');
    }
}