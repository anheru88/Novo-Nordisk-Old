<?php

namespace App\Filament\Resources\NegotiationConcepts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NegotiationConceptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('sap_concept')
                    ->label('Concepto SAP')
                    ->maxLength(255),
                TextInput::make('concept_percentage')
                    ->label('Porcentaje')
                    ->numeric()
                    ->step('0.01')
                    ->suffix('%'),
                Toggle::make('concept_compress')
                    ->label('Comprimir concepto')
                    ->default(false),
            ]);
    }
}
