<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string|null $entry_id
 * @property float $amount
 * @property string|null $related_party_name
 * @property string|null $related_party_account
 * @property Carbon $date
 * @property string|null $description
 * @property string $type
 * @property string $contribution_period_id
 * */
class BankTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'entry_id',
        'amount',
        'related_party_name',
        'related_party_account',
        'date',
        'description',
        'type',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
