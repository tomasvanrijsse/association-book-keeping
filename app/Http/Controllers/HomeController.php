<?php

namespace App\Http\Controllers;

use App\Models\CounterSuggestion;
use App\Models\Setting;
use App\Models\BankTransaction;

class HomeController extends Controller {

    public function index(){
        $data = array(
            'amountOfCounterSuggestions' => 0, //CounterSuggestion::query()->where('status', 1)->count(),
            'lastImport' => Setting::query()->where('key','LAST_IMPORT')->value('value'),
            'lastTransaction' => BankTransaction::query()->max('date')
        );

        return view('home/index', $data);
    }

    public function rescanCounter(){
        //find new counter transactions
        CounterSuggestion::findCounterTransactions();

        return redirect('/home');
    }

}
