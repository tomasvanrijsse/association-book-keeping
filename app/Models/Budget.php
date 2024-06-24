<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model {

    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('title', 'asc');
        });
    }

    function getBalanceAttribute()
    {
        $amount = BudgetMutation::query()->where('budget_id', $this->id)->sum('amount');

        return round($amount, 2);
    }
}
