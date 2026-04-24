<?php

namespace App\Filament\Resources\ProductMeasureUnits\Pages;

use App\Filament\Resources\ProductMeasureUnits\ProductMeasureUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductMeasureUnits extends ListRecords
{
    protected static string $resource = ProductMeasureUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
