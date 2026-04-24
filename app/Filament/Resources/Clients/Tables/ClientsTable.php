<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('clientChannel.name')
                    ->label('Canal')
                    ->badge()
                    ->sortable(),
                TextColumn::make('sap_code')
                    ->label('Código SAP')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('clientType.name')
                    ->label('Tipo de cliente')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('payterm.name')
                    ->label('Forma de pago')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('credit')
                    ->label('Cupo')
                    ->money('COP', 0)
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('active')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                        default => 'Sin definir',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
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
