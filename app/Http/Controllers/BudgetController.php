<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BudgetController extends Controller {

    public function index(){
        return view('budgets/index', [
            'budgets' => Budget::all()
        ]);
    }

    public function create(Request $request){
        $budget = new budget();
        $budget->title = $request->input('title');
        $budget->target = $request->input('target');
        $budget->save();

        return redirect('/budgets');
    }

    public function delete(Budget $budget)
    {
        $budget->delete();
        return response()->noContent();
    }
}
