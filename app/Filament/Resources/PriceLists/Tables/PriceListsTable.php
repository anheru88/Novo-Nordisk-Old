<?php

namespace App\Filament\Resources\PriceLists\Tables;

use App\Filament\Resources\PriceLists\PriceListResource;
use App\Models\PriceList;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PriceListsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre de lista')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->url(fn (PriceList $record): string => PriceListResource::getUrl('view', ['record' => $record])),
                TextColumn::make('created_at')
                    ->label('Subida el')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('active')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        '1' => 'Activa',
                        '2' => 'Pendiente',
                        '0' => 'Inactiva',
                        default => 'Sin definir',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'warning',
                        '0' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
