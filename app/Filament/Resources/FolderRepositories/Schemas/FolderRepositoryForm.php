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
                TextInput::make('folder_name')
                    ->label('Nombre de la carpeta')
                    ->required()
                    ->maxLength(191)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('folder_url', Str::slug((string) $state))),
                TextInput::make('folder_url')
                    ->label('URL (slug)')
                    ->maxLength(191)
                    ->helperText('Se genera automáticamente a partir del nombre.')
                    ->dehydrateStateUsing(fn ($state, callable $get) => Str::slug((string) ($state ?: $get('folder_name')))),
                Select::make('id_parent')
                    ->label('Carpeta padre')
                    ->options(fn (?FolderRepository $record) => FolderRepository::query()
                        ->when($record, fn ($q) => $q->whereKeyNot($record->getKey()))
                        ->orderBy('folder_name')
                        ->pluck('folder_name', 'id_folder'))
                    ->searchable()
                    ->placeholder('Raíz')
                    ->default(0)
                    ->dehydrateStateUsing(fn ($state) => (int) ($state ?: 0)),
            ]);
    }
}
