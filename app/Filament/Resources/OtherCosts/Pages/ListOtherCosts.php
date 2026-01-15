<?php

namespace App\Filament\Resources\OtherCosts\Pages;

use App\Filament\Resources\OtherCosts\OtherCostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOtherCosts extends ListRecords
{
    protected static string $resource = OtherCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
