<?php

namespace App\Presenters;

use App\Models\Budget;

/**
 * Class BudgetPresenter
 */
class BudgetPresenter extends Presenter
{
    /**
     * @var Budget
     */
    protected $model;

    /**
     * @return string
     */
    public function saldo()
    {
        $amount = $this->model->getSaldoAttribute();
        /* @var \Money\Money $amount */
        return '&euro; '.$amount->getAmount()/100;
    }

}