<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $budget_id
 * @property $bedrag
 * @property $datum
 * @property $transactie_id
 * @property $creditgroep_id
 */
class Booking extends Model {

    protected $table = 'boeking';

    protected $guarded = [];

}
