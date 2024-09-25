<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class BankTransaction extends Model {

    use SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('date', 'desc');
        });
    }


    public function budgetMutation(): HasMany
    {
        return $this->hasMany(BudgetMutation::class);
    }

    public function scopeOnBudget(Builder $query, Budget $budget){
        $query->whereHas('budgetMutation', function (Builder $query) use ($budget) {
            $query->where('budget_id', $budget->id);
        });
    }

    /** CUSTOM transaction FUNCTIONS **/

    public function deactivate(){
        //transaction uit + debit uitschakelen
        $this->db->where('id',$this->id);
        $this->db->update($this->getTableName(), array('status' => '0'));

        //credit boeking verwijderen
        $this->db->where('transactie_id',$this->id);
        $this->db->delete('boeking');
    }

}
