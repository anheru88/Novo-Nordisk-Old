<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Brand;
use App\Models\ProductLine;
use App\Models\ProductMeasureUnit;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('created_by')
                    ->default(fn () => auth()->id()),

                Section::make('Identificación')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Producto')
                            ->required()
                            ->maxLength(191),
                        TextInput::make('commercial_name')
                            ->label('Nombre comercial')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('generic_name')
                            ->label('Nombre genérico')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('sap_code')
                            ->label('Código SAP')
                            ->required()
                            ->maxLength(191),
                        TextInput::make('invima_reg')
                            ->label('Registro INVIMA')
                            ->required()
                            ->maxLength(191),
                        TextInput::make('cum')
                            ->label('CUM')
                            ->maxLength(191),
                        Select::make('prod_line_id')
                            ->label('Línea')
                            ->options(fn () => ProductLine::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        Select::make('brand_id')
                            ->label('Marca')
                            ->options(fn () => Brand::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Presentación')
                    ->columns(2)
                    ->schema([
                        Textarea::make('package')
                            ->label('Presentación')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('package_unit')
                            ->label('Unidad de empaque')
                            ->required()
                            ->maxLength(255),
                        Select::make('measure_unit_id')
                            ->label('Unidad de medida')
                            ->options(fn () => ProductMeasureUnit::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        TextInput::make('concentration')
                            ->label('Concentración')
                            ->maxLength(255),
                        TextInput::make('commercial_unit')
                            ->label('Unidad comercial')
                            ->maxLength(255),
                    ]),

                Section::make('Vigencia')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('valid_date_ini')
                            ->label('Vigencia desde'),
                        DatePicker::make('valid_date_end')
                            ->label('Vigencia (hasta)'),
                        TextInput::make('status')
                            ->label('Estatus')
                            ->maxLength(255),
                        TextInput::make('is_regulated')
                            ->label('Regulado')
                            ->maxLength(255)
                            ->default('0'),
                        Toggle::make('arp_divide')
                            ->label('Divide ARP'),
                    ]),

                Section::make('Notas')
                    ->schema([
                        Textarea::make('comments')
                            ->label('Comentarios')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
