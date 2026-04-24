<?php

namespace App\Filament\Resources\PaymentTerms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentTermForm
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
                TextInput::make('percent')
                    ->label('Descuento')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->suffix('%'),
                TextInput::make('code')
                    ->label('Código')
                    ->maxLength(255),
            ]);
    }
}
