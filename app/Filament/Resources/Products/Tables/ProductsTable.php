<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('generic_name')
                    ->label('Nombre genérico')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('prodLine.name')
                    ->label('Línea')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('package')
                    ->label('Presentación')
                    ->wrap()
                    ->limit(80)
                    ->tooltip(fn (Product $record): ?string => $record->package),
                IconColumn::make('is_current')
                    ->label('Vigente')
                    ->boolean()
                    ->getStateUsing(fn (Product $record): bool => $record->valid_date_end === null
                        || $record->valid_date_end >= now()),
                TextColumn::make('valid_date_end')
                    ->label('Vigencia (hasta)')
                    ->date()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
