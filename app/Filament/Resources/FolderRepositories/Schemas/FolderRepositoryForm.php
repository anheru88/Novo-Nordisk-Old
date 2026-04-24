<?php

namespace App\Filament\Resources\FolderRepositories\Schemas;

use App\Models\FolderRepository;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FolderRepositoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre de la carpeta')
                    ->required()
                    ->maxLength(191)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('url', Str::slug((string) $state))),
                TextInput::make('url')
                    ->label('URL (slug)')
                    ->maxLength(191)
                    ->helperText('Se genera automáticamente a partir del nombre.')
                    ->dehydrateStateUsing(fn ($state, callable $get) => Str::slug((string) ($state ?: $get('name')))),
                Select::make('id_parent')
                    ->label('Carpeta padre')
                    ->options(fn (?FolderRepository $record) => FolderRepository::query()
                        ->when($record, fn ($q) => $q->whereKeyNot($record->getKey()))
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->default(0)
                    ->dehydrateStateUsing(fn ($state) => (int) ($state ?: 0)),
            ]);
    }
}
