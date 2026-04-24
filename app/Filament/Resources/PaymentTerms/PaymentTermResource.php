<?php

namespace App\Filament\Resources\PaymentTerms;

use App\Filament\Resources\PaymentTerms\Pages\ListPaymentTerms;
use App\Filament\Resources\PaymentTerms\Schemas\PaymentTermForm;
use App\Filament\Resources\PaymentTerms\Tables\PaymentTermsTable;
use App\Models\PaymentTerm;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PaymentTermResource extends Resource
{
    protected static ?string $model = PaymentTerm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Datos del sistema';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'Método de pago';

    protected static ?string $pluralModelLabel = 'Métodos de pago';

    protected static ?string $navigationLabel = 'Métodos de pago ✓';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PaymentTermForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentTermsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) PaymentTerm::query()->count();
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
            'index' => ListPaymentTerms::route('/'),
        ];
    }
}
