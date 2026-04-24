<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('name')
                    ->label('Cliente')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                Select::make('client_channel_id')
                    ->label('Canal')
                    ->relationship('clientChannel', 'name')
                    ->required()
                    ->preload(),
                TextInput::make('sap_code')
                    ->label('Código SAP')
                    ->maxLength(191),
                Select::make('client_type_id')
                    ->label('Tipo de cliente')
                    ->relationship('clientType', 'name')
                    ->required()
                    ->preload(),
                Select::make('payterm_id')
                    ->label('Forma de pago')
                    ->relationship('payterm', 'name')
                    ->preload(),
                TextInput::make('credit')
                    ->label('Cupo')
                    ->numeric()
                    ->prefix('COP'),
                Select::make('active')
                    ->label('Estado')
                    ->options([
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                    ])
                    ->native(false)
                    ->default('1'),
                TextInput::make('nit')
                    ->label('NIT')
                    ->maxLength(191),
                TextInput::make('sap_name')
                    ->label('Nombre SAP')
                    ->maxLength(191),
            ]);
    }
}
