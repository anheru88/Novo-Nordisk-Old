<?php

namespace App\Filament\Resources\PriceLists\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PriceListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre de lista')
                    ->required()
                    ->maxLength(191)
                    ->columnSpanFull(),
                TextInput::make('version')
                    ->label('Versión')
                    ->required()
                    ->maxLength(50),
                Select::make('authorizer_user_id')
                    ->label('Autorizado por')
                    ->relationship('authorizer', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(fn () => auth()->id()),
                Select::make('active')
                    ->label('Estado')
                    ->options([
                        '1' => 'Activa',
                        '2' => 'Pendiente',
                        '0' => 'Inactiva',
                    ])
                    ->native(false)
                    ->default('1'),
                Textarea::make('comments')
                    ->label('Comentarios')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
