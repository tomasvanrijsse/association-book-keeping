<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BudgetController extends Controller {

    public function index(){
        return view('budgets/index', [
            'budgetten' => Budget::all()
        ]);
    }

    public function create(Request $request){
        $budget = new budget();
        $budget->naam = $request->input('naam');
        $budget->save();

        return redirect($request->input('redirectUrl'));
    }

    public function delete(Budget $budget)
    {
        $budget->delete();
        return response()->noContent();
    }
}
