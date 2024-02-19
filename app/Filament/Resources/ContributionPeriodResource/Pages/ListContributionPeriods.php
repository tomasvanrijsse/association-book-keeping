<?php

namespace App\Filament\Resources\ContributionPeriodResource\Pages;

use App\Filament\Resources\ContributionPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContributionPeriods extends ListRecords
{
    protected static string $resource = ContributionPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
