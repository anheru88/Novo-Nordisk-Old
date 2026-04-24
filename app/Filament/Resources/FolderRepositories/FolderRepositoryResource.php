<?php

namespace App\Filament\Resources\FolderRepositories;

use App\Filament\Resources\FolderRepositories\Pages\CreateFolderRepository;
use App\Filament\Resources\FolderRepositories\Pages\EditFolderRepository;
use App\Filament\Resources\FolderRepositories\Pages\ListFolderRepositories;
use App\Filament\Resources\FolderRepositories\RelationManagers\DocRepositoryRelationManager;
use App\Filament\Resources\FolderRepositories\Schemas\FolderRepositoryForm;
use App\Models\FolderRepository;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class FolderRepositoryResource extends Resource
{
    protected static ?string $model = FolderRepository::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'documentos/folder-repositories';

    protected static ?string $modelLabel = 'Carpeta';

    protected static ?string $pluralModelLabel = 'Docs Genéricos';

    protected static ?string $recordTitleAttribute = 'folder_name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['folder_name'];
    }

    public static function form(Schema $schema): Schema
    {
        return FolderRepositoryForm::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            DocRepositoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFolderRepositories::route('/'),
            'create' => CreateFolderRepository::route('/create'),
            'edit' => EditFolderRepository::route('/{record}/edit'),
        ];
    }
}
