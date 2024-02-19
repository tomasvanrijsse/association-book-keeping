<?php

namespace App\Filament\Resources\BankTransactionResource\Pages;

use App\Filament\Resources\BankTransactionResource;
use App\Models\BankTransaction;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Genkgo\Camt\Config;
use Genkgo\Camt\Reader;

class ListBankTransactions extends ListRecords
{
    protected static string $resource = BankTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('import')
                ->form([
                    FileUpload::make('transactions-xml')
                        ->label('Bank transactions export (CAMT053 XML)')
                        ->required()
                        ->storeFiles(false)
                        ->acceptedFileTypes(['text/xml']),
                ])
                ->action(function (array $data): void {
                    $xmlString = $data['transactions-xml']?->get() ?? '';

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
                                continue;
                            }

                            $relatedParty = $entry->getTransactionDetail()?->getRelatedParty();

                            $transaction->amount = $entry->getAmount()->absolute()->getAmount() / 100;
                            if ($relatedParty) {
                                $transaction->related_party_name = $relatedParty->getRelatedPartyType()->getName();
                                $transaction->related_party_account = $relatedParty->getAccount()?->getIdentification();
                            }
                            $transaction->date = new Carbon($entry->getBookingDate());
                            $transaction->description = $entry->getTransactionDetail()?->getAdditionalTransactionInformation();
                            $transaction->type = $entry->getIndex() ? 'credit' : 'debit';

                            $transaction->save();
                        }
                    }

                }),
        ];
    }
}
