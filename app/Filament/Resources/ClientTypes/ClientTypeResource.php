<?php

namespace App\Filament\Resources\ClientTypes;

use App\Filament\Resources\ClientTypes\Pages\ListClientTypes;
use App\Filament\Resources\ClientTypes\Schemas\ClientTypeForm;
use App\Filament\Resources\ClientTypes\Tables\ClientTypesTable;
use App\Models\ClientType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ClientTypeResource extends Resource
{
    protected static ?string $model = ClientType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Datos del sistema';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Tipo de cliente';

    protected static ?string $pluralModelLabel = 'Tipos de cliente';

    protected static ?string $navigationLabel = 'Tipos de cliente ✓';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ClientTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientTypesTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) ClientType::query()->count();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClientTypes::route('/'),
        ];
    }
}
