<?php

namespace App\Filament\Resources\PriceLists\RelationManagers;

use App\Models\ProductAuthLevel;
use App\Models\ProductPrice;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class CommercialPricesRelationManager extends RelationManager
{
    protected static string $relationship = 'productxprices';

    protected static ?string $title = 'Comerciales';

    protected const CHANNEL_ID = 5;

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('prod_sap_code')
            ->modifyQueryUsing(fn ($query) => $query->with('product'))
            ->columns([
                TextColumn::make('product.name')
                    ->label('Producto')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('v_commercial_price')
                    ->label('Precio Comercial')
                    ->money('COP', 0)
                    ->sortable(),
                TextColumn::make('increment_max')
                    ->label('Precio Máximo')
                    ->money('COP', 0),
                TextColumn::make('discount_n2')
                    ->label('Descuento Nivel 2')
                    ->state(fn (ProductPrice $r): ?string => $this->discountFor($r, 2)),
                TextColumn::make('discount_n3')
                    ->label('Descuento Nivel 3')
                    ->state(fn (ProductPrice $r): ?string => $this->discountFor($r, 3)),
                TextColumn::make('discount_n4')
                    ->label('Descuento Nivel 4')
                    ->state(fn (ProductPrice $r): ?string => $this->discountFor($r, 4)),
                TextColumn::make('valid_date_ini')
                    ->label('Vigencia desde')
                    ->date('Y-m-d'),
                TextColumn::make('valid_date_end')
                    ->label('Vigencia hasta')
                    ->date('Y-m-d'),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }

    protected function discountFor(ProductPrice $record, int $level): ?string
    {
        $levels = $this->levels($record->pricelists_id);

        $row = $levels->firstWhere(fn (ProductAuthLevel $l) => $l->product_id === $record->product_id
            && $l->dist_channel_id === static::CHANNEL_ID
            && $l->level_discount_id === $level);

        return $row?->discount_value;
    }

    protected function levels(int $pricelistId): Collection
    {
        static $cache = [];

        $key = static::class.':'.$pricelistId;

        return $cache[$key] ??= ProductAuthLevel::query()
            ->where('pricelists_id', $pricelistId)
            ->where('dist_channel_id', static::CHANNEL_ID)
            ->get();
    }
}
