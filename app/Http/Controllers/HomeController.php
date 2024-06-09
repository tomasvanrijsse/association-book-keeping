<?php

namespace App\Http\Controllers;

use App\Models\CounterSuggestion;
use App\Models\Setting;
use App\Models\Transaction;

class HomeController extends Controller {

    public function index(){
        $data = array(
            'aantalCounter' => CounterSuggestion::query()->where('status', 1)->count(),
            'last_import' => Setting::query()->where('key','LAST_IMPORT')->value('value'),
            'last_transaction' => Transaction::query()->max('datum')
        );

        return view('home/index', $data);
    }

    public function rescanCounter(){
        //find new counter transactions
        CounterSuggestion::findCounterTransactions();

        return redirect('/home');
    }

}
