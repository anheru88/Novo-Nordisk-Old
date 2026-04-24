<?php

namespace App\Filament\Resources\NegotiationConcepts;

use App\Filament\Resources\NegotiationConcepts\Pages\ListNegotiationConcepts;
use App\Filament\Resources\NegotiationConcepts\Schemas\NegotiationConceptForm;
use App\Filament\Resources\NegotiationConcepts\Tables\NegotiationConceptsTable;
use App\Models\NegotiationConcept;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class NegotiationConceptResource extends Resource
{
    protected static ?string $model = NegotiationConcept::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperClip;

    protected static string|UnitEnum|null $navigationGroup = 'Datos del sistema';

    protected static ?int $navigationSort = 70;

    protected static ?string $modelLabel = 'Concepto de negociación';

    protected static ?string $pluralModelLabel = 'Conceptos de negociación';

    protected static ?string $navigationLabel = 'Conceptos de negociación ✓';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return NegotiationConceptForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NegotiationConceptsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) NegotiationConcept::query()->count();
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
            'index' => ListNegotiationConcepts::route('/'),
        ];
    }
}
