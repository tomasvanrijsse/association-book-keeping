<?php

namespace App\Http\Controllers;
use App\Http\Requests\UploadImportRequest;
use App\Models\BankTransaction;
use App\Models\Setting;
use Carbon\Carbon;
use Genkgo\Camt\Config;
use Genkgo\Camt\Reader;
use Illuminate\Http\RedirectResponse;

class ImportController extends Controller {

    public function __invoke(UploadImportRequest $request): RedirectResponse
    {
        Setting::updateOrCreate(
            [ 'key' => 'LAST_IMPORT' ] ,
            [ 'value' => date('d-m-Y H:i')]
        );

        $newCount = 0;
        $existingCount = 0;


        $xmlString = $request->file('bankExport')->getContent() ?? '';

        $reader = new Reader(Config::getDefault());
        $message = $reader->readString($xmlString);
        $statements = $message->getRecords();

        foreach ($statements as $statement) {
            $entries = $statement->getEntries();

            foreach ($entries as $entry) {
                /** @var BankTransaction $transaction */
                $transaction = BankTransaction::query()
                    ->firstOrNew(['entry_id' => $entry->getReference()]);

                if ($transaction->exists) {
                    $existingCount++;
                    continue;
                }
                $newCount++;

                $relatedParty = $entry->getTransactionDetail()?->getRelatedParty();

                $transaction->amount = $entry->getAmount()->absolute()->getAmount() / 100;
                if ($relatedParty) {
                    $transaction->related_party_name = $relatedParty->getRelatedPartyType()->getName();
                    $transaction->related_party_account = $relatedParty->getAccount()?->getIdentification();
                }
                $transaction->date = new Carbon($entry->getBookingDate());
                $transaction->description = $entry->getTransactionDetail()?->getRemittanceInformation()->getUnstructuredBlock()->getMessage();
                $transaction->type = $entry->getCreditDebitIndicator() === "CRDT" ? 'credit' : 'debit';

                $transaction->save();
            }
        }

        $totalCount = $existingCount + $newCount;
        if($newCount == 0){
            $request->session()->flash('home_import_notice','Alle transacties waren al eerder geïmporteerd');
        } else {
            $request->session()->flash('home_import_success', 'Er zijn '.$newCount.' van de '.$totalCount.' transacties geïmporteerd ('.floor($newCount/$totalCount*100).'%)');
            if($existingCount>0){
                $request->session()->flash('home_import_notice', $existingCount.' transacties waren al eerder geïmporteerd');
            }
        }

        return redirect()->route('home');
    }

}
