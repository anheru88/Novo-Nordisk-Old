<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(125)
                    ->unique(ignoreRecord: true)
                    ->helperText('Formato recomendado: modulo.accion (e.g. users.index)'),
                TextInput::make('guard_name')
                    ->label('Guard')
                    ->required()
                    ->default('web')
                    ->maxLength(125),
            ]);
    }
}
