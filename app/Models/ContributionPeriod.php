<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ContributionPeriod extends Model {

    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }

    public function budgetMutations(): HasMany
    {
        return $this->hasMany(BudgetMutation::class);
    }

    public function credit(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->transactions->sum('amount'),
        );
    }

    public function debit(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->budgetMutations->sum('amount'),
        );
    }

    public function balance(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->credit - $this->debit,
        );
    }

}
