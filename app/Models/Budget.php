<?php

namespace App\Models;

use App\Presenters\BudgetPresenter;
use Illuminate\Database\Eloquent\Model;
use Money\Money;

class Budget extends Model
{

    public $table = 'budget';

    /**
     * @return Money
     */
    public function getSaldoAttribute()
    {
        $saldo = (int) Booking::where('budget_id',$this->id)->sum('bedrag');

        return Money::EUR($saldo * 100);
    }

    /**
     * @return BudgetPresenter
     */
    public function present()
    {
        return new BudgetPresenter($this);
    }


    public function budgetTarget()
    {
        return $this->hasOne('App\Models\BudgetTarget');
    }
}