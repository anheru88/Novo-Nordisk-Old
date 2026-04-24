<?php

namespace App\Filament\Resources\NegotiationConcepts\Pages;

use App\Filament\Resources\NegotiationConcepts\NegotiationConceptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNegotiationConcepts extends ListRecords
{
    protected static string $resource = NegotiationConceptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
