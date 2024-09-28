<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use App\Models\BudgetMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

class ExportsController extends Controller
{
    public function __invoke(Request $request)
    {
        $writer = new \OpenSpout\Writer\XLSX\Writer();
        $writer->openToBrowser("transactions-{$request->input('year')}.xlsx");

        $sheet = $writer->getCurrentSheet();
        $sheet->setName('Transactions '. $request->input('year'));

        $transactions = BankTransaction::query()->with('contributionPeriod','budgetMutations.budget')
            ->where(DB::raw('YEAR(date)'), '=', $request->year)
            ->orderBy('date')
            ->get();

        $writer->addRow(Row::fromValuesWithStyles(
            ['entry_id', 'type', 'bedrag', 'van naam','van rekening', 'omschrijving','contributie periode','budget'],
            (new Style())->setFontBold()
        ));

        /** @var BankTransaction $transaction */
        foreach($transactions as $transaction) {
            $row = new Row([
                Cell::fromValue($transaction->entry_id),
                Cell::fromValue($transaction->type),
                Cell::fromValue($transaction->amount),
                Cell::fromValue($transaction->related_party_name),
                Cell::fromValue($transaction->related_party_account),
                Cell::fromValue($transaction->description),
                Cell::fromValue($transaction->contributionPeriod?->title ?? ''),
                Cell::fromValue($transaction->budgetMutations->first()?->budget->title ?? ""),
            ]);
            $writer->addRow($row);
        }

        $writer->close();
    }
}
