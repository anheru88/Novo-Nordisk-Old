<?php

namespace App\Filament\Resources\PriceLists\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PriceListInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalle de la lista')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nombre de lista')
                            ->weight('bold')
                            ->columnSpanFull(),
                        TextEntry::make('version')
                            ->label('Versión'),
                        TextEntry::make('authorizer.name')
                            ->label('Autorizado por')
                            ->placeholder('—'),
                        TextEntry::make('active')
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
                            }),
                        TextEntry::make('created_at')
                            ->label('Subida el')
                            ->dateTime('Y-m-d H:i'),
                        TextEntry::make('updated_at')
                            ->label('Actualizado')
                            ->dateTime('Y-m-d H:i'),
                        TextEntry::make('comments')
                            ->label('Comentarios')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
