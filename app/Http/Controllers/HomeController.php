<?php

namespace App\Http\Controllers;

use App\Models\CounterSuggestion;
use App\Models\Setting;
use App\Models\BankTransaction;

class HomeController extends Controller {

    public function index() {
        $data = array(
            'lastImport' => Setting::query()->where('key','LAST_IMPORT')->value('value'),
            'lastTransaction' => BankTransaction::query()->max('date')
        );

        return view('home/index', $data);
    }

}
