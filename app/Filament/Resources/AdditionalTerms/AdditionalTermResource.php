<?php

namespace App\Filament\Resources\AdditionalTerms;

use App\Filament\Resources\AdditionalTerms\Pages\ListAdditionalTerms;
use App\Filament\Resources\AdditionalTerms\Schemas\AdditionalTermForm;
use App\Filament\Resources\AdditionalTerms\Tables\AdditionalTermsTable;
use App\Models\AdditionalTerm;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AdditionalTermResource extends Resource
{
    protected static ?string $model = AdditionalTerm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static string|UnitEnum|null $navigationGroup = 'Datos del sistema';

    protected static ?int $navigationSort = 50;

    protected static ?string $modelLabel = 'Uso adicional';

    protected static ?string $pluralModelLabel = 'Usos adicionales';

    protected static ?string $navigationLabel = 'Usos adicionales ✓';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AdditionalTermForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdditionalTermsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) AdditionalTerm::query()->count();
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
            'index' => ListAdditionalTerms::route('/'),
        ];
    }
}
