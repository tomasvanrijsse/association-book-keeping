<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property $id
 * @property $naam
 */
class Budget extends Model {

    use SoftDeletes;

    protected $table = 'budget';

    function getSaldoAttribute()
    {
        $amount = Booking::query()->where('budget_id', $this->id)->sum('bedrag');
        return round($amount, 2);
    }

    public function create(){
        parent::create();

        $boeking = new boeking();
        $boeking->bedrag = 0;
        $boeking->budget_id = $this->id;
        $boeking->_created = date('Y-m-d');
    }
}
