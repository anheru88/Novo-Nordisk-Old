<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del usuario')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->maxLength(255),
                        DateTimePicker::make('email_verified_at')
                            ->label('Verificado en'),
                    ]),
                Section::make('Roles y permisos')
                    ->schema([
                        Select::make('roles')
                            ->label('Roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->options(fn () => Role::orderBy('name')->pluck('name', 'id'))
                            ->helperText('Asignar uno o más roles. El rol admin otorga bypass total; inactivo bloquea acceso al panel.'),
                        CheckboxList::make('permissions')
                            ->label('Permisos directos (opcional)')
                            ->relationship('permissions', 'name')
                            ->columns(3)
                            ->bulkToggleable()
                            ->searchable()
                            ->columnSpanFull()
                            ->helperText('Permisos otorgados directamente al usuario, adicionales a los heredados por rol.'),
                    ]),
            ]);
    }
}
