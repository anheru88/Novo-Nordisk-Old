<?php

namespace App\Filament\Resources\PriceLists\RelationManagers;

use App\Models\ProductAuthLevel;
use App\Models\ProductPrice;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
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
                    ->formatStateUsing(fn (?string $state): string => static::formatMax($state)),
                TextColumn::make('discount_n2')
                    ->label('Descuento Nivel 2')
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->color('info')
                    ->state(fn (ProductPrice $r): string => $this->discountPercent($r, 2) ?? '—')
                    ->description(fn (ProductPrice $r): string => $this->discountPrice($r, 2), position: 'above'),
                TextColumn::make('discount_n3')
                    ->label('Descuento Nivel 3')
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->color('info')
                    ->state(fn (ProductPrice $r): string => $this->discountPercent($r, 3) ?? '—')
                    ->description(fn (ProductPrice $r): string => $this->discountPrice($r, 3), position: 'above'),
                TextColumn::make('discount_n4')
                    ->label('Descuento Nivel 4')
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->color('info')
                    ->state(fn (ProductPrice $r): string => $this->discountPercent($r, 4) ?? '—')
                    ->description(fn (ProductPrice $r): string => $this->discountPrice($r, 4), position: 'above'),
                TextColumn::make('valid_date_ini')
                    ->label('Vigencia desde')
                    ->date('d-m-Y'),
                TextColumn::make('valid_date_end')
                    ->label('Vigencia hasta')
                    ->date('d-m-Y'),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }

    protected function discountPrice(ProductPrice $record, int $level): string
    {
        $row = $this->levelRow($record, $level);
        $price = $row?->discount_price;

        if ($price === null || ! is_numeric($price)) {
            return '—';
        }

        return '$'.number_format((float) $price, 0, ',', '.');
    }

    protected function discountPercent(ProductPrice $record, int $level): ?string
    {
        $row = $this->levelRow($record, $level);
        $value = $row?->discount_value;

        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? $value.'%' : $value;
    }

    protected function levelRow(ProductPrice $record, int $level): ?ProductAuthLevel
    {
        return $this->levels($record->pricelists_id)
            ->firstWhere(fn (ProductAuthLevel $l) => $l->product_id === $record->product_id
                && $l->dist_channel_id === static::CHANNEL_ID
                && $l->level_discount_id === $level);
    }

    protected static function formatMax(?string $state): string
    {
        if ($state === null || $state === '' || strcasecmp($state, 'N/A') === 0) {
            return 'N/A';
        }

        return is_numeric($state)
            ? '$'.number_format((float) $state, 0, ',', '.')
            : $state;
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
