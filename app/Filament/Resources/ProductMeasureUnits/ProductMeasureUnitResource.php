<?php

namespace App\Filament\Resources\ProductMeasureUnits;

use App\Filament\Resources\ProductMeasureUnits\Pages\ListProductMeasureUnits;
use App\Filament\Resources\ProductMeasureUnits\Schemas\ProductMeasureUnitForm;
use App\Filament\Resources\ProductMeasureUnits\Tables\ProductMeasureUnitsTable;
use App\Models\ProductMeasureUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProductMeasureUnitResource extends Resource
{
    protected static ?string $model = ProductMeasureUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|UnitEnum|null $navigationGroup = 'Datos del sistema';

    protected static ?int $navigationSort = 40;

    protected static ?string $modelLabel = 'Unidad de venta';

    protected static ?string $pluralModelLabel = 'Unidades de venta';

    protected static ?string $navigationLabel = 'Unidades de venta ✓';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProductMeasureUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductMeasureUnitsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) ProductMeasureUnit::query()->count();
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
            'index' => ListProductMeasureUnits::route('/'),
        ];
    }
}
