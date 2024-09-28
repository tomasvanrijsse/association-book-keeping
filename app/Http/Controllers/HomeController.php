<?php

namespace App\Http\Controllers;

use App\Models\CounterSuggestion;
use App\Models\Mandate;
use App\Models\Setting;
use App\Models\BankTransaction;
use Carbon\Carbon;

class HomeController extends Controller {

    public function index() {
        $data = array(
            'lastImport' => new Carbon(Setting::query()->where('key','LAST_IMPORT')->value('value')),
            'lastTransaction' => new Carbon(BankTransaction::query()->max('date')),
            'mandatesWithoutBudget' => Mandate::whereNull('budget_id')->count(),
        );

        return view('home/index', $data);
    }

}
