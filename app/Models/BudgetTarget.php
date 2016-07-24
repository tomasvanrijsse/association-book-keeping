<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetTarget extends Model
{

    public $table = 'budgettarget';

    function current_target($saldo, $lastcreditgrouptime)
    {
        if ($this->id == "") {
            return false;
        }

        switch ($this->type) {
            case 'vast':
                $target = $this->bedrag;
                break;
            case 'target':
                $date1    = new DateTime(date('Y-m-d', $lastcreditgrouptime));
                $date2    = new DateTime($this->datum);
                $interval = $date1->diff($date2);
                $months   = $interval->y * 12 + $interval->m;
                if ($months <= 1) {
                    return $this->bedrag;
                }
                $target = $saldo + ($this->bedrag - $saldo) / $months;
                break;
            case 'increment':
                $target = $saldo + $this->bedrag;
                break;
        }

        return round($target, 2);

    }

    public function budget()
    {
        return $this->belongsTo('App\Models\Budget');
    }

}