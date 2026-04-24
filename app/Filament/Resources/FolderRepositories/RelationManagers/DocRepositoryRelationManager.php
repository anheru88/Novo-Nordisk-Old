<?php

namespace App\Filament\Resources\FolderRepositories\RelationManagers;

use App\Models\FolderRepository;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DocRepositoryRelationManager extends RelationManager
{
    protected static string $relationship = 'docRepository';

    protected static ?string $title = 'Documentos';

    protected static ?string $modelLabel = 'Documento';

    protected static ?string $pluralModelLabel = 'Documentos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('name')
                    ->label('Archivo')
                    ->disk('public')
                    ->directory(fn () => 'uploads/'.$this->folderPath())
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    ->required()
                    ->maxSize(51200),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Archivo')
                    ->formatStateUsing(fn (?string $state) => $state ? basename($state) : '—')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Subido')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                Action::make('download')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => $record->name ? Storage::disk('public')->url($record->name) : null, shouldOpenInNewTab: true)
                    ->visible(fn ($record) => $record->name && Storage::disk('public')->exists($record->name)),
                DeleteAction::make()
                    ->after(fn ($record) => $record->name
                        ? Storage::disk('public')->delete($record->name)
                        : null),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function folderPath(): string
    {
        /** @var FolderRepository $folder */
        $folder = $this->getOwnerRecord();

        return $folder->url ?: (string) $folder->getKey();
    }
}
