<?php

namespace App\Filament\Resources\AdditionalTerms\Pages;

use App\Filament\Resources\AdditionalTerms\AdditionalTermResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdditionalTerms extends ListRecords
{
    protected static string $resource = AdditionalTermResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
