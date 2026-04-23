<?php

namespace App\Filament\Resources\FolderRepositories\Pages;

use App\Filament\Resources\FolderRepositories\FolderRepositoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFolderRepository extends EditRecord
{
    protected static string $resource = FolderRepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
