<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property ?string $entry_id
 * @property double $amount
 * @property ?string $related_party_name
 * @property ?string $related_party_account
 * @property Carbon $date
 * @property ?string $description
 * @property string $type
 *
 * @property ContributionPeriod $contributionPeriod
 */
class BankTransaction extends Model {

    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
    }

    public function budgetMutations(): HasMany
    {
        return $this->hasMany(BudgetMutation::class);
    }

    public function contributionPeriod(): BelongsTo
    {
        return $this->belongsTo(ContributionPeriod::class);
    }

    public function scopeOnBudget(Builder $query, Budget $budget){
        $query->whereHas('budgetMutations', function (Builder $query) use ($budget) {
            $query->where('budget_id', $budget->id);
        });
    }

}
