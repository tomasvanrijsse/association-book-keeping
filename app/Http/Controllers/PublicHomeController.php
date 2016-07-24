<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\BudgetTarget;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class PublicHomeController extends Controller {
       
    public function index(){
        $last_transaction = DB::table('transactie')->max('datum');

        $budgets = Budget::with('budgetTarget')->orderBy('account_id')->orderBy('naam')->get();

        $data = array(
            'last_import' => Setting::find(config('abk.settings.lastImport'))->value,
            'last_transaction' => $last_transaction,
            'budgetten' => $budgets
        );

        return view('public_home/index', $data);
    }
    
    public function login(){
        $this->session->set_userdata('isAdmin','true');
        redirect('/');
    }
}