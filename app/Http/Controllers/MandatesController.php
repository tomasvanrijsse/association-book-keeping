<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Mandate;
use Illuminate\Http\Request;

class MandatesController extends Controller
{
    public function index()
    {
        return view('mandates.index', [
            'mandates' => Mandate::all(),
            'budgets' => Budget::all(),
        ]);
    }

    public function store(Request $request){
        foreach($request->input('mandate') as $mandateId => $budgetId){
            $mandate = Mandate::find($mandateId);
            $mandate->budget_id = $budgetId;
            $mandate->save();
        }

        return redirect()->route('mandates');
    }
}
