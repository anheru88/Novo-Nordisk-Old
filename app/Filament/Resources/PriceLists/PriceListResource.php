<?php

namespace App\Filament\Resources\PriceLists;

use App\Filament\Resources\PriceLists\Pages\ListPriceLists;
use App\Filament\Resources\PriceLists\Pages\ViewPriceList;
use App\Filament\Resources\PriceLists\RelationManagers\CommercialPricesRelationManager;
use App\Filament\Resources\PriceLists\RelationManagers\InstitutionalPricesRelationManager;
use App\Filament\Resources\PriceLists\Schemas\PriceListForm;
use App\Filament\Resources\PriceLists\Schemas\PriceListInfolist;
use App\Filament\Resources\PriceLists\Tables\PriceListsTable;
use App\Models\PriceList;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PriceListResource extends Resource
{
    protected static ?string $model = PriceList::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'Lista de precios';

    protected static ?string $pluralModelLabel = 'Precios';

    protected static ?string $navigationLabel = 'Precios ⚠';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PriceListForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PriceListInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PriceListsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) PriceList::query()->count();
    }

    /**
     * @return array<NavigationItem>
     */
    public static function getNavigationItems(): array
    {
        return array_map(
            fn (NavigationItem $item): NavigationItem => $item->extraAttributes(['class' => 'fi-sidebar-item-warning']),
            parent::getNavigationItems(),
        );
    }

    public static function getRelations(): array
    {
        return [
            InstitutionalPricesRelationManager::class,
            CommercialPricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPriceLists::route('/'),
            'view' => ViewPriceList::route('/{record}'),
        ];
    }
}
