<?php

namespace App\Filament\Resources\ClientFiles;

use App\Filament\Resources\ClientFiles\Pages\ListClientFiles;
use App\Models\ClientFile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

class ClientFileResource extends Resource
{
    protected static ?string $model = ClientFile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'documentos/client-files';

    protected static ?string $modelLabel = 'Documento de cliente';

    protected static ?string $pluralModelLabel = 'Docs de Clientes';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getPages(): array
    {
        return [
            'index' => ListClientFiles::route('/'),
        ];
    }
}
