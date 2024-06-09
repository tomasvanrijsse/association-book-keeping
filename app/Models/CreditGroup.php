<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * @property $id
 * @property $naam
 * @property $status
 * @property $jaar
 */
class CreditGroup extends Model {

    protected $table = 'creditgroep';

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class,  'creditgroep_id', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class,  'creditgroep_id', 'id');
    }

    public function credit(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->transactions->sum('bedrag'),
        );
    }

    public function debit(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->bookings->sum('bedrag'),
        );
    }

    public function saldo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->credit - $this->debet,
        );
    }

}
